<?php

namespace App\Services\AuditSMPData;

use App\Helpers\JsonResponse;
use App\Models\Area;
use App\Models\Auditor;
use App\Models\AuditSMP;
use App\Models\AuditSmpData;
use App\Models\AuditSMPScore;
use App\Services\ActivityLog\ActivityLogService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuditSMPDataService
{
    protected $logService;

    public function __construct(ActivityLogService $logService)
    {
        $this->logService = $logService;
    }
    
   public function getAllAuditData($limit = 10, $paginate = true, $with = [], $lead_id = null, $unit_id = null)
    {
        try {
            $order  = request('order', 'ASC');
            $search = request('q', '');
            $ref    = request('ref', 'order');
            $start  = request('start', null);
            $end    = request('end', null);

            $query = AuditSmpData::query();

            if (!empty($with)) {
                $query->with($with);
            }

            if (!is_null($lead_id)) {
                $query->where(function ($q) use ($lead_id) {
                    $q->where('auditor_lead_id', $lead_id)
                    ->orWhereHas('auditors', function ($q2) use ($lead_id) {
                        $q2->where('users.id', $lead_id);
                    });
                });
            }

            if (!is_null($unit_id)) {
                $query->where('unit_id', $unit_id);
            }

            if ($start && $end) {
                $end = date('Y-m-d', strtotime($end . ' +1 day'));
                $query->whereBetween('created_at', [$start, $end]);
            } elseif ($start) {
                $query->whereDate('created_at', '>=', $start);
            } elseif ($end) {
                $query->whereDate('created_at', '<=', $end);
            }

            $allowedSort = ['id', 'created_at'];
            if (!in_array($ref, $allowedSort)) {
                $ref = 'id';
            }

            $query->orderBy($ref, $order);

            if ($paginate) {
                $data = $query->paginate($limit)->withQueryString();
            } else {
                $data = $limit > 0
                    ? $query->limit($limit)->get()
                    : $query->get();
            }

            return JsonResponse::success($data, 'Audit data found', 200);

        } catch (Exception $e) {

            $this->logService->log(
                'audit_smp_data.fetch_all',
                'Failed to fetch audit data',
                500,
                [
                    'error' => $e->getMessage(),
                    'params' => request()->all(),
                ]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'Audit data not found',
                500
            );
        }
    }

    public function createAuditData(array $data)
    {
        DB::beginTransaction();

        try {
            $audit = AuditSmpData::create([
                'unit_id'   => $data['unit_id'],
                'auditor_lead_id' => $data['auditor_lead_id'],
                'start_audit' => $data['start_audit'],
                'end_audit' => $data['end_audit'],
                'created_by' => auth()->id(),
            ]);

            $auditData = AuditSMP::with([
                    'pernyataan.kriteria.evidence',
                    'kriteria.evidence'
                    ])->where('type', 'header')->get();

            foreach ($auditData as $auditItem) {

                $headerAudit = AuditSMPScore::create([
                    'name'              => $auditItem->name,
                    'bobot'           => $auditItem->bobot,
                    'type'           => $auditItem->type,
                    'audit_smp_data_id' => $audit->id,
                    'created_by'             => auth()->id(),
                ]);

                foreach ($auditItem->pernyataan as $pernyataan) {

                    $pernyataanAudit = AuditSMPScore::create([
                        'name'              => $pernyataan->name,
                        'type'              => $pernyataan->type,
                        'parent_id'         => $headerAudit->id,
                        'audit_smp_data_id' => $audit->id,
                        'created_by'        => auth()->id(),
                    ]);

                    foreach ($pernyataan->kriteria as $kriteria) {

                        $kriteriaAudit = AuditSMPScore::create([
                        'name'              => $kriteria->name,
                        'type'              => $kriteria->type,
                        'parent_id'         => $pernyataanAudit->id,
                        'audit_smp_data_id' => $audit->id,
                        'created_by'        => auth()->id(),
                    ]);

                        foreach ($kriteria->evidence as $evidence) {

                            AuditSMPScore::create([
                                'name'              => $evidence->name,
                                'type'              => $evidence->type,
                                'parent_id'         => $kriteriaAudit->id,
                                'audit_smp_data_id' => $audit->id,
                                'created_by'        => auth()->id(),
                            ]);
                        }
                    }
                }

                foreach ($auditItem->kriteria as $kriteria) {

                    $kriteriaAudit = AuditSMPScore::create([
                        'name'              => $kriteria->name,
                        'type'              => $kriteria->type,
                        'parent_id'         => $headerAudit->id,
                        'audit_smp_data_id' => $audit->id,
                        'created_by'        => auth()->id(),
                    ]);

                    foreach ($kriteria->evidence as $evidence) {

                        AuditSMPScore::create([
                            'name'              => $evidence->name,
                            'type'              => $evidence->type,
                            'parent_id'         => $kriteriaAudit->id,
                            'audit_smp_data_id' => $audit->id,
                            'created_by'        => auth()->id(),
                        ]);
                    }
                }
            }

            $auditors = [];
            foreach ($data['auditors_ids'] as $auditorId) {
                $auditors[] = [
                    'audit_smp_data_id' => $audit->id,
                    'user_id' => $auditorId,
                    'created_by' => auth()->id(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            if (!empty($auditors)) {
                DB::table('auditors')->insert($auditors);
            }

            DB::commit();

            $this->logService->log(
                'audit-data.create',
                'Create audit data success',
                201,
                [
                    'audit_data_id' => $audit->id,
                    'payload' => $data,
                ]
            );

            return JsonResponse::success(
                $audit,
                'Audit data created successfully',
                201
            );

        } catch (Exception $e) {

            DB::rollBack();

            $this->logService->log(
                'audit-data.create',
                'Failed to create audit data',
                500,
                [
                    'error'   => $e->getMessage(),
                    'payload' => $data,
                ]
            );
            return JsonResponse::error(
                $e->getMessage(),
                'Failed to create audit data',
                500
            );
        }
    }

    public function updateAuditData(AuditSmpData $audit, array $data)
    {
        DB::beginTransaction();

        try {
            $audit->load('auditors');
            $before = $audit->toArray();

            $updateData = [
                'unit_id'   => $data['unit_id'],
                'auditor_lead_id' => $data['auditor_lead_id'],
                'start_audit' => $data['start_audit'],
                'end_audit' => $data['end_audit'],
                'updated_by' => auth()->id(),
            ];

            $audit->update($updateData);

            Auditor::where('audit_smp_data_id', $audit->id)->delete();
            $auditors = [];
            foreach ($data['auditors_ids'] as $auditorId) {
                $auditors[] = [
                    'audit_smp_data_id' => $audit->id,
                    'user_id' => $auditorId,
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            if (!empty($auditors)) {
                DB::table('auditors')->insert($auditors);
            }

            DB::commit();

            $this->logService->log(
                'audit-data.update',
                'Update audit data success',
                200,
                [
                    'before' => $before,
                    'after'  => $audit->toArray(),
                ]
            );

            return JsonResponse::success(
                $audit,
                'Audit data updated',
                200
            );

        } catch (Exception $e) {
            DB::rollBack();

            $this->logService->log(
                'audit-data.update',
                'Failed to update audit data',
                500,
                [
                    'error' => $e->getMessage(),
                    'payload' => $data,
                ]
            );

            throw $e;
        }
    }

    public function deleteAuditData(AuditSmpData $audit)
    {
        DB::beginTransaction();

        try {
            $logData = [
                'audit_id'   => $audit->id,
                'audit_name' => $audit->name,
            ];

            $audit->update([
                'deleted_by' => auth()->id(),
            ]);

            AuditSMPScore::where('audit_smp_data_id', $audit->id)->update([
                'deleted_by' => auth()->id(),
            ]);
            AuditSMPScore::where('audit_smp_data_id', $audit->id)->delete();
            Auditor::where('audit_smp_data_id', $audit->id)->update([
                'deleted_by' => auth()->id(),
            ]);
            Auditor::where('audit_smp_data_id', $audit->id)->delete();

            $audit->delete();

            DB::commit();

            $this->logService->log(
                'audit.data.delete',
                'Delete audit data success',
                200,
                $logData
            );

            return JsonResponse::success(
                null,
                'Audit data deleted successfully',
                200
            );

        } catch (Exception $e) {

            DB::rollBack();

            $this->logService->log(
                'audit.data.delete',
                'Failed to delete audit data',
                500,
                [
                    'audit_id' => $audit->id ?? null,
                    'error'   => $e->getMessage(),
                ]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'Failed to delete audit data',
                500
            );
        }
    }

    public function sendAuditData(AuditSmpData $audit)
    {
        DB::beginTransaction();

        try {
            $logData = [
                'audit_id'   => $audit->id,
                'audit_name' => $audit->name,
            ];

            $audit->update([
                'status' => 1,
                'updated_by' => auth()->id(),
            ]);

            DB::commit();

            $this->logService->log(
                'audit.data.send',
                'Send audit data success',
                200,
                $logData
            );

            return JsonResponse::success(
                null,
                'Audit data sent successfully',
                200
            );

        } catch (Exception $e) {

            DB::rollBack();

            $this->logService->log(
                'audit.data.send',
                'Failed to send audit data',
                500,
                [
                    'audit_id' => $audit->id ?? null,
                    'error'   => $e->getMessage(),
                ]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'Failed to send audit data',
                500
            );
        }
    }

    public function updateAuditScore(AuditSMPScore $audit_score, array $data)
    {
        DB::beginTransaction();

        try {
            $before = $audit_score->toArray();

            $updateData = [
                'rekomendasi' => $data['rekomendasi_' . $audit_score->id] ?? null,
                'temuan' => $data['temuan_' . $audit_score->id] ?? null,
                'due_date' => $data['due_date_' . $audit_score->id] ?? null,
                'pic' => $data['pic_' . $audit_score->id] ?? null,
                'updated_by' => auth()->id(),
            ];

            $audit_score->update($updateData);

            DB::commit();

            $this->logService->log(
                'audit.score.update',
                'Update audit score success',
                200,
                [
                    'before' => $before,
                    'after'  => $audit_score->toArray(),
                ]
            );

            return JsonResponse::success(
                $audit_score,
                'Audit score updated',
                200
            );

        } catch (Exception $e) {
            DB::rollBack();

            $this->logService->log(
                'audit.score.update',
                'Failed to update audit score',
                500,
                [
                    'error' => $e->getMessage(),
                    'payload' => $data,
                ]
            );

            throw $e;
        }
    }

    public function updateAuditAchievement(AuditSMPScore $audit_score, array $data)
    {
        DB::beginTransaction();

        try {
            $before = $audit_score->toArray();

            $updateData = [
                'pencapaian_nilai_kriteria' => $data['pencapaian_nilai_kriteria_' . $audit_score->id] ?? null,
                'updated_by' => auth()->id(),
            ];

            $audit_score->update($updateData);

            DB::commit();

            $this->logService->log(
                'audit.achievement.update',
                'Update audit achievement success',
                200,
                [
                    'before' => $before,
                    'after'  => $audit_score->toArray(),
                ]
            );

            return JsonResponse::success(
                $audit_score,
                'Audit achievement updated',
                200
            );

        } catch (Exception $e) {
            DB::rollBack();dd($e->getMessage());

            $this->logService->log(
                'audit.achievement.update',
                'Failed to update audit achievement',
                500,
                [
                    'error' => $e->getMessage(),
                    'payload' => $data,
                ]
            );

            throw $e;
        }
    }

    public function updateEvidenceAudit(Request $request, AuditSMPScore $audit_score, array $data)
    {
        DB::beginTransaction();

        try {
            $before = $audit_score->toArray();

            $attachmentFile = null;
            
            if($request->hasFile('evidence_file_'.$audit_score->id))
            {      
                $file= $request->file('evidence_file_'.$audit_score->id);
                $file_name = 'evidence-file-' . time() .'.'. $file->getClientOriginalExtension();
                if ($audit_score->evidence_file) {
                    unlink(public_path('uploads/evidence_file/'.$audit_score->evidence_file));
                }
                $file->move(public_path('uploads/evidence_file/'),$file_name);   
                $attachmentFile = $file_name;
            }
            
            $audit_score->evidence_file = $attachmentFile ?? $audit_score->evidence_file;

            $updateData = [
                'evidence_file' => $attachmentFile ?? $audit_score->evidence_file,
                'updated_by' => auth()->id(),
            ];

            $audit_score->update($updateData);

            DB::commit();

            $this->logService->log(
                'audit.evidence.update',
                'Update audit evidence success',
                200,
                [
                    'before' => $before,
                    'after'  => $audit_score->toArray(),
                ]
            );

            return JsonResponse::success(
                $audit_score,
                'Audit evidence updated',
                200
            );

        } catch (Exception $e) {
            DB::rollBack();

            $this->logService->log(
                'audit.evidence.update',
                'Failed to update audit evidence',
                500,
                [
                    'error' => $e->getMessage(),
                    'payload' => $data,
                ]
            );

            throw $e;
        }
    }
}
