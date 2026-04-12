<?php

namespace App\Services\Securepedia;

use App\Helpers\JsonResponse;
use App\Models\Attribute;
use App\Models\Securepedia;
use App\Services\ActivityLog\ActivityLogService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SecurepediaService
{
    protected $logService;

    public function __construct(ActivityLogService $logService)
    {
        $this->logService = $logService;
    }
    
   public function getAllSecurepedia($limit = 10, $paginate = true, $type = null)
    {
        try {
            $order  = request('order', 'DESC');
            $search = request('q', '');
            $ref    = request('ref', 'id');
            $start  = request('start', null);
            $end    = request('end', null);

            $query = Securepedia::query();

            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%");
                });
            }

            if (!empty($type)) {
                $query->where('type', $type);
            }

            if ($start && $end) {
                $end = date('Y-m-d', strtotime($end . ' +1 day'));
                $query->whereBetween('created_at', [$start, $end]);
            } elseif ($start) {
                $query->whereDate('created_at', '>=', $start);
            } elseif ($end) {
                $query->whereDate('created_at', '<=', $end);
            }

            $allowedSort = ['id', 'title', 'created_at'];
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

            return JsonResponse::success($data, 'Securepedias found', 200);

        } catch (Exception $e) {

            $this->logService->log(
                'securepedia.fetch_all',
                'Failed to fetch securepedias',
                500,
                [
                    'error' => $e->getMessage(),
                    'params' => request()->all(),
                ]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'Securepedias not found',
                500
            );
        }
    }

    public function createSecurepedia(Request $request)
    {
        DB::beginTransaction();

        try {
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $file_name = 'securepedia-' . time() . '.' . $file->getClientOriginalExtension();

                $file->move(public_path('uploads/securepedia/'), $file_name);
                $questionFile = $file_name;
            }

            $attribute = Securepedia::create([
                'title' => $request->input('title'),
                'type' => $request->input('type'),
                'file' => $questionFile ?? null,
                'created_by' => auth()->id(),
            ]);
            
            DB::commit();

            $this->logService->log(
                'securepedia.create',
                'Create securepedia',
                201,
                [
                    'securepedia_id' => $attribute->id,
                    'title'   => $attribute->title,
                    'type'    => $attribute->type,
                ]
            );

            return JsonResponse::success(
                $attribute,
                'Securepedia created',
                201
            );
        } catch (Exception $e) {
            DB::rollBack();

            $this->logService->log(
                'securepedia.create',
                'Failed to create securepedia',
                500,
                [
                    'error' => $e->getMessage(),
                    'payload' => [
                        'title' => $request->input('title'),
                        'type' => $request->input('type'),
                    ],
                ]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'Failed to create securepedia',
                500
            );
        }
    }

    public function updateSecurepedia(Securepedia $securepedia, Request $request)
    {
        DB::beginTransaction();

        try {
            $before = $securepedia->toArray();

            $filePath = $securepedia->file;
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $file_name = 'question-file-' . time() . '.' . $file->getClientOriginalExtension();

                if ($securepedia->file) {
                    $oldPath = public_path('uploads/securepedia/' . $securepedia->file);
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }

                $file->move(public_path('uploads/securepedia/'), $file_name);
                $filePath = $file_name;
            }


            $updateData = [
                'title'       => $request->input('title'),
                'type'      => $request->input('type'),
                'file' => $filePath,
                'updated_by' => auth()->id(),
            ];

            $securepedia->update($updateData);

            DB::commit();

            $this->logService->log(
                'securepedia.update',
                'Update securepedia',
                200,
                [
                    'before' => $before,
                    'after'  => $securepedia->toArray(),
                ]
            );

            return JsonResponse::success(
                $securepedia,
                'Securepedia updated',
                201
            );

        } catch (Exception $e) {
            DB::rollBack();

            $this->logService->log(
                'securepedia.update',
                'Failed to update securepedia',
                500,
                [
                    'error' => $e->getMessage(),
                    'payload' => $request->all(),
                ]
            );

            throw $e;
        }
    }

    public function deleteSecurepedia(Securepedia $securepedia)
    {
        DB::beginTransaction();

        try {
            $securepedia->update([
                'deleted_by' => auth()->id(),
            ]);

            $securepedia->delete();

            DB::commit();

            $this->logService->log(
                'securepedia.delete',
                'Delete securepedia',
                201,
                [
                    'securepedia_id' => $securepedia->id,
                    'title'   => $securepedia->title,
                ]
            );

            return JsonResponse::success(
                null,
                'Securepedia deleted',
                201
            );

        } catch (Exception $e) {
            DB::rollBack();

            $this->logService->log(
                'securepedia.delete',
                'Failed to delete securepedia',
                500,
                [
                    'securepedia_id' => $securepedia->id ?? null,
                    'error'   => $e->getMessage(),
                ]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'Failed to delete securepedia',
                500
            );
        }
    }
}
