<?php

namespace App\Models\Concerns;

use App\Mail\AssessmentValidationFinished;
use App\Models\Unit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Masa sanggah setelah validasi Pusat selesai (Maturity & KPI).
 * rebuttal_state: 0 belum, 1 Unit menyusun sanggahan, 2 dikirim ke MMRK, 3 dikirim ke Pusat, 4 selesai sanggah.
 * Sanggahan memakai ulang alur status biasa (0 draft -> 1 MMRK -> 2 Pusat -> 3 selesai) dan hanya bisa sekali.
 */
trait HasRebuttal
{
    public static function rebuttalDays(): int
    {
        return 7;
    }

    /** Batas akhir mengajukan sanggahan (akhir hari ke-7); null untuk data lama tanpa waktu validasi. */
    public function rebuttalDeadline(): ?Carbon
    {
        return $this->validated_at ? Carbon::parse($this->validated_at)->addDays(self::rebuttalDays())->endOfDay() : null;
    }

    public function canRebut(): bool
    {
        $deadline = $this->rebuttalDeadline();

        return (int) $this->status === 3
            && (int) $this->rebuttal_state === 0
            && $deadline !== null
            && now()->lte($deadline);
    }

    /** Mulai sanggahan: kembali ke draft agar Unit bisa mengubah data yang belum divalidasi. */
    public function startRebuttal(): bool
    {
        if (!$this->canRebut()) {
            return false;
        }

        $this->update([
            'status'              => 0,
            'send_status'         => false,
            'rebuttal_state'      => 1,
            'rebuttal_started_at' => now(),
            'updated_by'          => auth()->id(),
        ]);

        return true;
    }

    public function isRebuttalEditing(): bool
    {
        return (int) $this->rebuttal_state === 1 && (int) $this->status === 0;
    }

    /** Email ke user role Unit / Pusat di unit ini: validasi selesai + batas sanggah. */
    public function notifyValidationFinished(string $typeLabel): void
    {
        $deadline = $this->rebuttalDeadline();
        if (!$deadline) {
            return;
        }

        $unitName = optional(Unit::find($this->unit_id))->name;
        $recipients = User::role(['Unit', 'Pusat'])->where('unit_id', $this->unit_id)->get()
            ->pluck('mail_address')->filter()->unique();

        foreach ($recipients as $to) {
            try {
                Mail::to($to)->send(new AssessmentValidationFinished($typeLabel, $unitName, $this->period_label, $deadline));
            } catch (\Throwable $th) {
                Log::error("Gagal mengirim email validasi selesai {$typeLabel}: " . $th->getMessage());
            }
        }
    }
}
