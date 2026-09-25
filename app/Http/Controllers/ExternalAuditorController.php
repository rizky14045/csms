<?php

namespace App\Http\Controllers;

use App\Mail\ExternalAuditorAccountCreated;
use App\Models\AuditSmpData;
use App\Models\ExternalAuditor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use RealRashid\SweetAlert\Facades\Alert;

/**
 * Pengelolaan auditor external per unit. Auditor external hanya bisa membuka halaman
 * Audit SMP untuk auditor, hanya pada data audit yang dipilih, dan sampai tanggal kedaluwarsa.
 */
class ExternalAuditorController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:view.external.auditor')->only(['index']);
        $this->middleware('can:create.external.auditor')->only(['create', 'store']);
        $this->middleware('can:edit.external.auditor')->only(['edit', 'update', 'resend']);
        $this->middleware('can:delete.external.auditor')->only(['destroy']);
    }

    protected function unitId(): int
    {
        $unitId = auth()->user()->unit_id;
        abort_if(!$unitId, 403, 'Akun Anda belum terhubung ke unit.');

        return (int) $unitId;
    }

    protected function ownRecord(ExternalAuditor $item): ExternalAuditor
    {
        abort_if((int) $item->unit_id !== $this->unitId(), 404);

        return $item;
    }

    /** Data audit unit ini yang bisa dipilih: hanya yang sudah selesai (status 3). */
    protected function unitAudits()
    {
        return AuditSmpData::where('unit_id', $this->unitId())->where('status', 3)->orderByDesc('start_audit')->orderByDesc('id')->get();
    }

    protected function auditLabel(AuditSmpData $audit): string
    {
        $fmt = fn($d) => $d ? \Carbon\Carbon::parse($d)->format('d-m-Y') : '-';

        return 'Audit SMP ' . $fmt($audit->start_audit) . ' s/d ' . $fmt($audit->end_audit);
    }

    protected function syncAudits(User $user, array $auditIds): array
    {
        // hanya audit milik unit ini yang sudah selesai yang boleh dipilih
        $valid = AuditSmpData::where('unit_id', $this->unitId())->where('status', 3)->whereIn('id', $auditIds)->pluck('id')->all();

        DB::table('external_auditor_audits')->where('user_id', $user->id)->delete();
        $now = now();
        DB::table('external_auditor_audits')->insert(array_map(fn($id) => [
            'user_id' => $user->id, 'audit_smp_data_id' => $id, 'created_at' => $now, 'updated_at' => $now,
        ], $valid));

        return $valid;
    }

    protected function rules(?User $user = null): array
    {
        return [
            'name'        => 'required|string|max:255',
            'email'       => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore(optional($user)->id)],
            'expired_at'  => 'required|date|after_or_equal:today',
            'audit_ids'   => 'required|array|min:1',
            'audit_ids.*' => 'integer',
        ];
    }

    protected function messages(): array
    {
        return [
            'name.required'             => 'Nama harus diisi!',
            'email.required'            => 'Email harus diisi!',
            'email.email'               => 'Format email tidak valid!',
            'email.unique'              => 'Email sudah terdaftar!',
            'expired_at.required'       => 'Tanggal expired harus diisi!',
            'expired_at.date'           => 'Tanggal expired tidak valid!',
            'expired_at.after_or_equal' => 'Tanggal expired tidak boleh sebelum hari ini!',
            'audit_ids.required'        => 'Pilih minimal satu data audit SMP yang sudah selesai!',
            'audit_ids.min'             => 'Pilih minimal satu data audit SMP yang sudah selesai!',
        ];
    }

    protected function generatePassword(): string
    {
        return Str::upper(Str::random(3)) . Str::lower(Str::random(4)) . random_int(100, 999) . '@' . Str::random(2);
    }

    protected function sendEmail(User $user, string $password, ExternalAuditor $record, array $auditIds, bool $isReset = false): bool
    {
        $labels = AuditSmpData::whereIn('id', $auditIds)->orderBy('start_audit')->get()->map(fn($a) => $this->auditLabel($a))->all();

        try {
            Mail::to($user->email)->send(new ExternalAuditorAccountCreated(
                $user->name, $user->email, $password, $record->expired_at, optional($record->unit)->name, $labels, $isReset
            ));

            return true;
        } catch (\Throwable $th) {
            Log::error('Gagal mengirim email auditor external: ' . $th->getMessage());

            return false;
        }
    }

    public function index()
    {
        $items = ExternalAuditor::with('user')
            ->where('unit_id', $this->unitId())
            ->orderByDesc('id')
            ->get();

        $auditsByUser = DB::table('external_auditor_audits')
            ->join('audit_smp_data', 'audit_smp_data.id', '=', 'external_auditor_audits.audit_smp_data_id')
            ->whereIn('external_auditor_audits.user_id', $items->pluck('user_id'))
            ->select('external_auditor_audits.user_id', 'audit_smp_data.start_audit', 'audit_smp_data.end_audit')
            ->get()
            ->groupBy('user_id');

        return view('external-auditor.index', ['items' => $items, 'auditsByUser' => $auditsByUser]);
    }

    public function create()
    {
        $this->unitId();

        return view('external-auditor.create', ['audits' => $this->unitAudits()]);
    }

    public function store(Request $request)
    {
        $unitId = $this->unitId();
        $data = $request->validate($this->rules(), $this->messages());

        $password = $this->generatePassword();

        $user = null;
        $record = null;
        $validIds = [];

        DB::transaction(function () use ($data, $unitId, $password, &$user, &$record, &$validIds) {
            // unit_id user sengaja kosong: akses ke data unit hanya lewat audit yang dipilih
            $user = new User();
            $user->forceFill([
                'name'       => $data['name'],
                'email'      => $data['email'],
                'password'   => bcrypt($password),
                'type'       => 'auditor',
                'login_type' => 0,
                'created_by' => auth()->id(),
            ])->save();
            $user->assignRole(ExternalAuditor::ROLE);

            $record = ExternalAuditor::create([
                'user_id'    => $user->id,
                'unit_id'    => $unitId,
                'expired_at' => $data['expired_at'],
                'created_by' => auth()->id(),
            ]);

            $validIds = $this->syncAudits($user, $data['audit_ids']);
        });

        $record->load('unit');
        $sent = $this->sendEmail($user, $password, $record, $validIds);

        if ($sent) {
            Alert::success('Tambah Berhasil', 'Auditor external berhasil dibuat dan username/password telah dikirim ke email.');
        } else {
            Alert::warning('Akun Dibuat, Email Gagal', 'Auditor external berhasil dibuat tetapi email gagal terkirim. Gunakan tombol "Kirim Ulang Akses" pada daftar.');
        }

        return redirect()->route('external-auditor.index');
    }

    public function edit(ExternalAuditor $item)
    {
        $this->ownRecord($item)->load('user');

        return view('external-auditor.edit', [
            'item'     => $item,
            'audits'   => $this->unitAudits(),
            'selected' => DB::table('external_auditor_audits')->where('user_id', $item->user_id)->pluck('audit_smp_data_id')->all(),
        ]);
    }

    public function update(Request $request, ExternalAuditor $item)
    {
        $this->ownRecord($item);

        $rules = $this->rules($item->user);
        // tanggal expired lama boleh dipertahankan walau sudah lewat; email tidak diubah
        $rules['expired_at'] = 'required|date';
        unset($rules['email']);
        $data = $request->validate($rules, $this->messages());

        DB::transaction(function () use ($item, $data) {
            $item->user->forceFill(['name' => $data['name'], 'updated_by' => auth()->id()])->save();
            $item->update(['expired_at' => $data['expired_at'], 'updated_by' => auth()->id()]);
            $this->syncAudits($item->user, $data['audit_ids']);
        });

        Alert::success('Update Berhasil', 'Data auditor external berhasil diubah.');

        return redirect()->route('external-auditor.index');
    }

    /** Buat password baru dan kirim ulang username/password ke email. */
    public function resend(ExternalAuditor $item)
    {
        $this->ownRecord($item)->load('user', 'unit');

        $password = $this->generatePassword();
        $item->user->forceFill(['password' => bcrypt($password), 'access_failed_count' => 0, 'locked_until' => null])->save();

        $auditIds = DB::table('external_auditor_audits')->where('user_id', $item->user_id)->pluck('audit_smp_data_id')->all();

        if ($this->sendEmail($item->user, $password, $item, $auditIds, true)) {
            Alert::success('Berhasil', 'Password baru telah dikirim ke email auditor.');
        } else {
            Alert::error('Email Gagal', 'Password diperbarui tetapi email gagal terkirim. Coba kirim ulang.');
        }

        return redirect()->route('external-auditor.index');
    }

    public function destroy(ExternalAuditor $item)
    {
        $this->ownRecord($item);

        DB::transaction(function () use ($item) {
            DB::table('external_auditor_audits')->where('user_id', $item->user_id)->delete();
            $item->user->forceFill(['deleted_by' => auth()->id(), 'session_id' => null])->save();
            $item->user->delete();
            $item->delete();
        });

        Alert::success('Delete Berhasil', 'Auditor external berhasil dihapus dan tidak dapat login lagi.');

        return redirect()->route('external-auditor.index');
    }
}
