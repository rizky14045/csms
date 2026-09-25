<?php

namespace App\Http\Controllers\User\MonthlyAudit;

use App\Http\Controllers\Controller;
use App\Http\Validation\AgreementExternalValidation;
use App\Http\Validation\AttributeValidation;
use App\Http\Validation\ResponsiblePersonValidation;
use App\Http\Validation\SecurityExternalValidation;
use App\Http\Validation\SecurityValidation;
use App\Models\MonthlyReport;
use App\Services\MonthlyReport\MasterSyncService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class ReportItemController extends Controller
{
    protected $sync;

    public function __construct(MasterSyncService $sync)
    {
        $this->sync = $sync;
    }

    /** Definisi form edit per section (hanya section bermode duplicate). */
    protected function fields($section)
    {
        $yn = ['Ya', 'Tidak'];
        $units = ['Unit', 'Lembar', 'Jumlah', 'Orang', 'Titik', 'Meter'];

        switch ($section) {
            case 'security':
                return [
                    'title' => 'Personil Satpam',
                    'back'  => 'user.monthly-audit.security-form.index',
                    'fields' => [
                        ['name' => 'name', 'label' => 'Nama', 'type' => 'text'],
                        ['name' => 'gender', 'label' => 'Jenis Kelamin', 'type' => 'select', 'options' => ['Pria', 'Wanita']],
                        ['name' => 'unit_work', 'label' => 'Unit Kerja', 'type' => 'text'],
                        ['name' => 'nid', 'label' => 'NID', 'type' => 'text'],
                        ['name' => 'registration_number', 'label' => 'Nomor REG KTA', 'type' => 'text'],
                        ['name' => 'expired_card_date', 'label' => 'Expired KTA', 'type' => 'date'],
                        ['name' => 'position', 'label' => 'Jabatan', 'type' => 'select', 'options' => ['Komandan', 'Anggota', 'Chief']],
                        ['name' => 'birth_place', 'label' => 'Tempat Lahir', 'type' => 'text'],
                        ['name' => 'birth_date', 'label' => 'Tanggal Lahir', 'type' => 'date'],
                        ['name' => 'qualification', 'label' => 'Kualifikasi', 'type' => 'select', 'options' => ['Pratama', 'Madya', 'Utama']],
                        ['name' => 'last_education', 'label' => 'Pendidikan Terakhir', 'type' => 'text'],
                        ['name' => 'note', 'label' => 'Catatan', 'type' => 'text'],
                    ],
                    'rules' => array_diff_key(SecurityValidation::rulesForUpdate(), ['kta_file' => 1]),
                    'messages' => SecurityValidation::messages(),
                ];
            case 'attribute':
                return [
                    'title' => 'Atribut / Sarana',
                    'back'  => 'user.monthly-audit.form-attribute.index',
                    'fields' => [
                        ['name' => 'name', 'label' => 'Nama', 'type' => 'text'],
                        ['name' => 'status_ownership', 'label' => 'Status Kepemilikan', 'type' => 'select', 'options' => ['BUJP', 'PNP']],
                        ['name' => 'unit', 'label' => 'Satuan', 'type' => 'select', 'options' => $units],
                        ['name' => 'standard_contract', 'label' => 'Standar Kontrak', 'type' => 'text'],
                        ['name' => 'type_attribute', 'label' => 'Tipe', 'type' => 'select', 'options' => ['Attribute', 'Sarana']],
                    ],
                    'rules' => array_merge(AttributeValidation::rulesForUpdate(), ['type_attribute' => 'required|in:Attribute,Sarana']),
                    'messages' => AttributeValidation::messages(),
                ];
            case 'person':
                return [
                    'title' => 'Penanggung Jawab',
                    'back'  => 'user.monthly-audit.worker-sum.index',
                    'fields' => [
                        ['name' => 'name', 'label' => 'Nama', 'type' => 'text'],
                        ['name' => 'position', 'label' => 'Jabatan', 'type' => 'text'],
                        ['name' => 'work_unit', 'label' => 'Unit Kerja', 'type' => 'text'],
                        ['name' => 'training_smp', 'label' => 'Training SMP', 'type' => 'select', 'options' => $yn],
                        ['name' => 'auditor_smp', 'label' => 'Auditor SMP', 'type' => 'select', 'options' => $yn],
                        ['name' => 'main', 'label' => 'MAIN', 'type' => 'select', 'options' => $yn],
                        ['name' => 'investigation', 'label' => 'Investigasi', 'type' => 'select', 'options' => $yn],
                        ['name' => 'mansrisk', 'label' => 'Mansrisk', 'type' => 'select', 'options' => $yn],
                        ['name' => 'stackholder_management', 'label' => 'Stakeholder Management', 'type' => 'select', 'options' => $yn],
                        ['name' => 'last_education', 'label' => 'Pendidikan Terakhir', 'type' => 'text'],
                        ['name' => 'note', 'label' => 'Keterangan', 'type' => 'text'],
                    ],
                    'rules' => array_merge(ResponsiblePersonValidation::rulesForUpdate(), ['last_education' => 'nullable|string', 'note' => 'nullable|string']),
                    'messages' => ResponsiblePersonValidation::messages(),
                ];
            case 'agreement':
                return [
                    'title' => 'Perjanjian Eksternal',
                    'back'  => 'user.monthly-audit.worker-sum.index',
                    'fields' => [
                        ['name' => 'instansi', 'label' => 'Instansi', 'type' => 'text'],
                        ['name' => 'name', 'label' => 'Nama', 'type' => 'text'],
                        ['name' => 'regional_unit', 'label' => 'Satuan Wilayah', 'type' => 'text'],
                        ['name' => 'pkt_number', 'label' => 'Nomor PKT', 'type' => 'text'],
                        ['name' => 'pkt_title', 'label' => 'Judul PKT', 'type' => 'text'],
                        ['name' => 'expired_date', 'label' => 'Tanggal Berakhir', 'type' => 'date'],
                        ['name' => 'note', 'label' => 'Keterangan', 'type' => 'text'],
                    ],
                    'rules' => array_merge(AgreementExternalValidation::rulesForUpdate(), ['note' => 'nullable|string']),
                    'messages' => AgreementExternalValidation::messages(),
                ];
            case 'security_external':
                return [
                    'title' => 'Pengamanan Eksternal',
                    'back'  => 'user.monthly-audit.worker-sum.index',
                    'fields' => [
                        ['name' => 'name', 'label' => 'Nama', 'type' => 'text'],
                        ['name' => 'gender', 'label' => 'Jenis Kelamin', 'type' => 'select', 'options' => ['Pria', 'Wanita']],
                        ['name' => 'instansi', 'label' => 'Instansi', 'type' => 'text'],
                        ['name' => 'regional_unit', 'label' => 'Satuan Wilayah', 'type' => 'text'],
                        ['name' => 'warrant_number', 'label' => 'Nomor Surat Perintah', 'type' => 'text'],
                        ['name' => 'note', 'label' => 'Keterangan', 'type' => 'select', 'options' => ['Polri', 'TNI']],
                    ],
                    'rules' => array_merge(SecurityExternalValidation::rulesForUpdate(), ['gender' => 'required|in:Pria,Wanita']),
                    'messages' => SecurityExternalValidation::messages(),
                ];
        }

        return null;
    }

    /** Ambil laporan (milik user, belum terkirim) + baris + salinan yang bisa diedit. */
    protected function resolve($monthlyId, $section, $rowId, $forWrite = true)
    {
        $cfg = MasterSyncService::config($section);
        $meta = $cfg && $cfg['mode'] === 'duplicate' ? $this->fields($section) : null;
        abort_unless($meta, 404);

        $report = MonthlyReport::findOrFail($monthlyId);
        abort_unless($report->unit_id == auth()->user()->unit_id, 404);
        if ($report->send_status) {
            abort(403, 'Laporan sudah dikirim');
        }

        $row = $cfg['row']::where('id', $rowId)->where('monthly_report_id', $report->id)->firstOrFail();
        $target = $cfg['master']::withTrashed()->find($row->{$cfg['fk']});
        abort_unless($target, 404);

        return [$report, $cfg, $meta, $row, $target];
    }

    /** Baris yang menunjuk langsung ke master asli dijadikan salinan dulu sebelum diedit. */
    protected function detach($report, $cfg, $row, $target)
    {
        if ($target->user_id === null) {
            return $target; // sudah salinan / data khusus laporan
        }

        $copy = null;
        DB::transaction(function () use ($cfg, $report, $row, $target, &$copy) {
            $attrs = $target->getAttributes();
            unset($attrs['id'], $attrs['deleted_at'], $attrs['deleted_by'], $attrs['updated_by']);
            $attrs['user_id'] = null;
            $attrs['source_id'] = $target->id;
            $attrs['created_at'] = now();
            $attrs['updated_at'] = now();
            foreach (($cfg['nullify'] ?? []) as $col) {
                $attrs[$col] = null;
            }

            $copy = $cfg['master']::create($attrs);
            $copy->forceFill(['source_id' => $target->id, 'user_id' => null])->save();
            $row->update([$cfg['fk'] => $copy->id]);
        });

        return $copy;
    }

    public function edit($monthlyId, $section, $rowId)
    {
        [$report, $cfg, $meta, $row, $target] = $this->resolve($monthlyId, $section, $rowId);

        return view('user.monthly-audit.row-edit', [
            'monthlyId' => $monthlyId,
            'section'   => $section,
            'rowId'     => $rowId,
            'meta'      => $meta,
            'item'      => $target,
            'hasSource' => $target->source_id !== null || $target->user_id !== null,
        ]);
    }

    public function update(Request $request, $monthlyId, $section, $rowId)
    {
        [$report, $cfg, $meta, $row, $target] = $this->resolve($monthlyId, $section, $rowId);

        $validator = Validator::make($request->all(), $meta['rules'], $meta['messages']);

        // Menyimpan ke master: No REG KTA tidak boleh kembar dengan satpam master lain.
        if ($section === 'security' && $request->boolean('save_to_master')) {
            $ownMasterId = $target->user_id !== null ? $target->id : $target->source_id;
            $validator->after(function ($v) use ($request, $ownMasterId) {
                if (\App\Rules\UniqueKtaNumber::exists($request->registration_number, $ownMasterId)) {
                    $v->errors()->add('registration_number', 'Nomor registrasi KTA sudah terdaftar pada satuan pengamanan lain!');
                }
            });
        }

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $names = array_column($meta['fields'], 'name');
        $data = array_intersect_key($request->all(), array_flip($names));
        foreach ($names as $n) {
            if (!array_key_exists($n, $data)) {
                $data[$n] = null;
            }
        }

        $wasReal = $target->user_id !== null;
        $sourceId = $wasReal ? $target->id : $target->source_id;
        $copy = $this->detach($report, $cfg, $row, $target);

        DB::transaction(function () use ($copy, $data, $request, $cfg, $sourceId, $wasReal) {
            $copy->update($data);

            if (!$request->boolean('save_to_master')) {
                return;
            }

            $original = $sourceId ? $cfg['master']::find($sourceId) : null; // find() mengabaikan yang sudah dihapus
            if ($original) {
                $original->update($data);
                return;
            }

            $attrs = array_merge($copy->getAttributes(), $data);
            unset($attrs['id'], $attrs['deleted_at'], $attrs['deleted_by'], $attrs['updated_by'], $attrs['source_id']);
            $attrs['user_id'] = auth()->id();
            $attrs['created_by'] = auth()->id();
            if ($cfg['master'] === \App\Models\Attribute::class) {
                $attrs['unit_id'] = auth()->user()->unit_id;
            }
            $created = $cfg['master']::create($attrs);
            $created->forceFill(['user_id' => auth()->id(), 'source_id' => null])->save();
            $copy->forceFill(['source_id' => $created->id])->save();
        });

        Alert::success('Berhasil', $request->boolean('save_to_master')
            ? 'Data laporan dan master data berhasil diperbarui!'
            : 'Data laporan berhasil diperbarui!');

        return redirect()->route($meta['back'], ['monthlyId' => $monthlyId]);
    }

    public function destroy(Request $request, $monthlyId, $section, $rowId)
    {
        [$report, $cfg, $meta, $row, $target] = $this->resolve($monthlyId, $section, $rowId);

        $deleteMaster = $request->boolean('delete_master');
        $isReal = $target->user_id !== null;
        $sourceId = $isReal ? $target->id : $target->source_id;

        DB::transaction(function () use ($row, $target, $cfg, $isReal, $sourceId, $deleteMaster, $report, $section) {
            $row->delete();

            if (!$isReal) {
                $target->update(['deleted_by' => auth()->id()]);
                $target->delete();
            }

            if ($sourceId) {
                $original = $cfg['master']::find($sourceId);
                if ($deleteMaster && $original) {
                    $original->update(['deleted_by' => auth()->id()]);
                    $original->delete();
                } elseif ($original) {
                    $this->sync->exclude($report, $section, $sourceId);
                }
            }
        });

        Alert::success('Berhasil', $deleteMaster
            ? 'Data dihapus dari laporan dan master data!'
            : 'Data dihapus dari laporan!');

        return redirect()->route($meta['back'], ['monthlyId' => $monthlyId]);
    }

    public function sync(Request $request, $monthlyId, $section)
    {
        $cfg = MasterSyncService::config($section);
        abort_unless($cfg, 404);

        $report = MonthlyReport::findOrFail($monthlyId);
        abort_unless($report->unit_id == auth()->user()->unit_id, 404);
        if ($report->send_status) {
            abort(403, 'Laporan sudah dikirim');
        }

        // Pulihkan program/detail yang pernah dihapus dari laporan ini, lalu salin ulang dari master.
        if ($section === 'program' && $request->boolean('restore')) {
            $this->sync->restoreExcludedPrograms($report);
        }

        $added = $this->sync->sync($section, $report);
        $updated = $this->sync->refreshFromMaster($section, $report);

        if ($added > 0 || $updated > 0) {
            $parts = [];
            if ($added > 0) {
                $parts[] = "{$added} data baru ditambahkan";
            }
            if ($updated > 0) {
                $parts[] = "{$updated} data diperbarui sesuai perubahan terbaru di master data";
            }
            Alert::success('Sinkron Berhasil', ucfirst(implode(' dan ', $parts)) . " pada {$cfg['label']}.");
        } else {
            $reason = $section === 'program'
                ? $this->sync->programSyncDiagnosis($report)
                : "Tidak ada data baru maupun pembaruan dari master data untuk {$cfg['label']}.";
            Alert::info('Tidak Ada Perubahan', $reason);
        }

        return redirect()->back();
    }
}
