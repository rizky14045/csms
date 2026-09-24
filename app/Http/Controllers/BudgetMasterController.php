<?php

namespace App\Http\Controllers;

use App\Models\BudgetMaster;
use App\Models\Unit;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

/**
 * Master data penyerapan anggaran, terpisah per unit.
 * Unit/UL hanya untuk unitnya sendiri; Admin & Pusat memilih unit.
 * Mengubah/menghapus master tidak memengaruhi laporan bulanan yang sudah ada
 * (baris laporan adalah salinan mandiri).
 */
class BudgetMasterController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:view.budget.master')->only(['index']);
        $this->middleware('can:create.budget.master')->only(['create', 'store']);
        $this->middleware('can:edit.budget.master')->only(['edit', 'update']);
        $this->middleware('can:delete.budget.master')->only(['destroy']);
    }

    protected function isGlobal(): bool
    {
        return auth()->user()->hasAnyRole(['Admin', 'Pusat']);
    }

    protected function authorizeItem(BudgetMaster $item): void
    {
        if (!$this->isGlobal() && (int) $item->unit_id !== (int) auth()->user()->unit_id) {
            abort(404);
        }
    }

    protected function rules(): array
    {
        return [
            'type'                => 'required|in:pemeliharaan,administrasi',
            'kode_aktifitas'      => 'required|string|max:255',
            'kode_prk'            => 'required|string|max:255',
            'deskripsi_kegiatan'  => 'required|string|max:255',
            'jumlah_anggaran'     => 'required|numeric|min:0',
            'penyerapan_anggaran' => 'nullable|numeric|min:0',
            'keterangan'          => 'nullable|string|max:255',
        ];
    }

    protected function messages(): array
    {
        return [
            'type.required'               => 'Jenis Anggaran harus diisi!',
            'type.in'                     => 'Jenis Anggaran tidak sesuai!',
            'kode_aktifitas.required'     => 'Kode Aktifitas harus diisi!',
            'kode_prk.required'           => 'Kode PRK harus diisi!',
            'deskripsi_kegiatan.required' => 'Deskripsi Kegiatan harus diisi!',
            'jumlah_anggaran.required'    => 'Jumlah Anggaran harus diisi!',
            'jumlah_anggaran.numeric'     => 'Jumlah Anggaran harus berupa angka!',
            'unit_id.required'            => 'Unit harus dipilih!',
            'unit_id.exists'              => 'Unit tidak valid!',
        ];
    }

    public function index(Request $request)
    {
        $query = BudgetMaster::with('unit')->orderBy('unit_id')->orderBy('type')->orderBy('id');

        if ($this->isGlobal()) {
            if ($request->filled('unit_id')) {
                $query->where('unit_id', (int) $request->unit_id);
            }
        } else {
            $query->where('unit_id', auth()->user()->unit_id);
        }

        return view('budget-master.index', [
            'items'    => $query->paginate(25)->withQueryString(),
            'isGlobal' => $this->isGlobal(),
            'units'    => $this->isGlobal() ? Unit::orderBy('name')->get() : collect(),
        ]);
    }

    public function create()
    {
        abort_if(!$this->isGlobal() && !auth()->user()->unit_id, 403);

        return view('budget-master.create', [
            'isGlobal' => $this->isGlobal(),
            'units'    => $this->isGlobal() ? Unit::orderBy('name')->get() : collect(),
        ]);
    }

    public function store(Request $request)
    {
        $rules = $this->rules();
        if ($this->isGlobal()) {
            $rules['unit_id'] = 'required|exists:units,id';
        }
        $data = $request->validate($rules, $this->messages());

        $unitId = $this->isGlobal() ? (int) $data['unit_id'] : auth()->user()->unit_id;
        abort_if(!$unitId, 403);
        unset($data['unit_id']);

        BudgetMaster::create($data + [
            'unit_id'    => $unitId,
            'user_id'    => auth()->id(),
            'created_by' => auth()->id(),
        ]);

        Alert::success('Tambah Berhasil', 'Master data penyerapan anggaran berhasil dibuat!');

        return redirect()->route('budget-master.index');
    }

    public function edit(BudgetMaster $item)
    {
        $this->authorizeItem($item);

        return view('budget-master.edit', ['item' => $item]);
    }

    public function update(Request $request, BudgetMaster $item)
    {
        $this->authorizeItem($item);
        $data = $request->validate($this->rules(), $this->messages());

        $item->update($data + ['updated_by' => auth()->id()]);

        Alert::success('Update Berhasil', 'Master data diperbarui. Laporan bulanan yang sudah ada tidak berubah.');

        return redirect()->route('budget-master.index');
    }

    public function destroy(BudgetMaster $item)
    {
        $this->authorizeItem($item);

        $item->update(['deleted_by' => auth()->id()]);
        $item->delete();

        Alert::success('Delete Berhasil', 'Master data dihapus. Laporan bulanan yang sudah ada tidak berubah.');

        return redirect()->route('budget-master.index');
    }
}
