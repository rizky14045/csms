<?php

namespace App\Services\Attribute;

use App\Models\Attribute;
use App\Models\User;
use App\Services\Unit\UnitScope;
use Illuminate\Support\Facades\DB;

/**
 * Pembagian jumlah standar kontrak attribute antara unit induk dan UL-nya.
 * Baris induk (parent_attribute_id null) menyimpan jumlah induk + contract_total;
 * tiap UL yang kebagian punya baris sendiri (unit_id = UL) yang menunjuk ke baris induk.
 */
class AttributeAllocationService
{
    /** Unit induk dengan UL (bukan UL) yang boleh mengatur pembagian. */
    public function canAllocate(User $user): bool
    {
        return UnitScope::isGroup($user) && !UnitScope::isUl($user);
    }

    /** [unit_id => jumlah] untuk induk + semua UL-nya. */
    public function allocationsFor(Attribute $parent): array
    {
        $result = [(int) $parent->unit_id => (int) $parent->standard_contract];
        foreach ($parent->children as $child) {
            $result[(int) $child->unit_id] = (int) $child->standard_contract;
        }

        return $result;
    }

    public function totalFor(Attribute $attribute): int
    {
        return (int) ($attribute->contract_total ?? $attribute->standard_contract);
    }

    /** Pesan error jika pembagian tidak pas dengan total kontrak; null jika valid. */
    public function validate(User $user, $total, $allocs): ?string
    {
        if (!is_numeric($total) || (int) $total < 0 || (int) $total != $total) {
            return 'Jumlah standar kontrak harus berupa angka bulat.';
        }

        $unitIds = UnitScope::visibleUnitIds($user);
        $allocs = is_array($allocs) ? $allocs : [];
        $sum = 0;
        foreach ($unitIds as $id) {
            $v = $allocs[$id] ?? 0;
            if (!is_numeric($v) || (int) $v < 0 || (int) $v != $v) {
                return 'Jumlah pembagian tiap unit harus berupa angka bulat.';
            }
            $sum += (int) $v;
        }

        if ($sum !== (int) $total) {
            return "Total pembagian induk + UL ({$sum}) harus sama dengan jumlah standar kontrak ({$total}).";
        }

        return null;
    }

    public function save(User $user, array $data, ?Attribute $parent = null): Attribute
    {
        return DB::transaction(function () use ($user, $data, $parent) {
            $total = (int) $data['standard_contract'];
            $allocs = $data['alloc'] ?? [];
            $shared = [
                'name'             => $data['name'],
                'status_ownership' => $data['status_ownership'],
                'unit'             => $data['unit'],
                'type_attribute'   => $data['type_attribute'],
                'contract_total'   => $total,
            ];
            $parentQty = (int) ($allocs[$user->unit_id] ?? 0);

            if ($parent) {
                $parent->update($shared + ['standard_contract' => (string) $parentQty, 'updated_by' => $user->id]);
            } else {
                $parent = Attribute::create($shared + [
                    'standard_contract' => (string) $parentQty,
                    'user_id'           => $user->id,
                    'unit_id'           => $user->unit_id,
                    'created_by'        => $user->id,
                ]);
            }

            foreach (UnitScope::visibleUnitIds($user) as $unitId) {
                if ((int) $unitId === (int) $user->unit_id) {
                    continue;
                }

                $qty = (int) ($allocs[$unitId] ?? 0);
                $child = Attribute::where('parent_attribute_id', $parent->id)->where('unit_id', $unitId)->first();

                if ($qty > 0) {
                    $payload = $shared + ['standard_contract' => (string) $qty];
                    if ($child) {
                        $child->update($payload + ['updated_by' => $user->id]);
                    } else {
                        Attribute::create($payload + [
                            'parent_attribute_id' => $parent->id,
                            'user_id'             => $user->id,
                            'unit_id'             => $unitId,
                            'created_by'          => $user->id,
                        ]);
                    }
                } elseif ($child) {
                    $child->update(['deleted_by' => $user->id]);
                    $child->delete();
                }
            }

            return $parent;
        });
    }

    public function delete(User $user, Attribute $parent): void
    {
        DB::transaction(function () use ($user, $parent) {
            foreach ($parent->children as $child) {
                $child->update(['deleted_by' => $user->id]);
                $child->delete();
            }
            $parent->update(['deleted_by' => $user->id]);
            $parent->delete();
        });
    }
}
