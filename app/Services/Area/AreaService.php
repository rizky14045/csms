<?php

namespace App\Services\Area;

use App\Helpers\JsonResponse;
use App\Models\Area;
use App\Models\CategoryAssesment;
use App\Services\ActivityLog\ActivityLogService;
use Exception;
use Illuminate\Support\Facades\DB;

class AreaService
{
    protected $logService;

    public function __construct(ActivityLogService $logService)
    {
        $this->logService = $logService;
    }
    
   public function getAllArea($limit = 10, $paginate = true, $type = null, $with = [])
    {
        try {
            $order  = request('order', 'ASC');
            $search = request('q', '');
            $ref    = request('ref', 'order');
            $start  = request('start', null);
            $end    = request('end', null);

            $query = Area::query();

            if (!empty($with)) {
                $query->with($with);
            }

            if ($type) {
                $query->where('type', $type);
            }

            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            }

            if ($start && $end) {
                $end = date('Y-m-d', strtotime($end . ' +1 day'));
                $query->whereBetween('created_at', [$start, $end]);
            } elseif ($start) {
                $query->whereDate('created_at', '>=', $start);
            } elseif ($end) {
                $query->whereDate('created_at', '<=', $end);
            }

            $allowedSort = ['id', 'name', 'created_at'];
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

            return JsonResponse::success($data, 'Area found', 200);

        } catch (Exception $e) {

            $this->logService->log(
                'area.fetch_all',
                'Failed to fetch areas',
                500,
                [
                    'error' => $e->getMessage(),
                    'params' => request()->all(),
                ]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'Area not found',
                500
            );
        }
    }

    public function createArea(array $data)
    {
        DB::beginTransaction();

        try {
            $lastarea = Area::where('type', $data['type'])->latest()->first();
            $order = $lastarea ? $lastarea->order + 1 : 1; 

            $area = Area::create([
                'name' => $data['name'],
                'order' => $order,
                'type' => $data['type'],
                'created_by' => auth()->id(),
            ]);
            
            DB::commit();

            $this->logService->log(
                'area.create',
                'Create area',
                201,
                [
                    'area_id' => $area->id,
                    'name'   => $area->name,
                    'order'    => $area->order,
                ]
            );

            return JsonResponse::success(
                $area,
                'Area created',
                201
            );
        } catch (Exception $e) {
            DB::rollBack();

            $this->logService->log(
                'area.create',
                'Failed to create area',
                500,
                [
                    'error' => $e->getMessage(),
                    'payload' => [
                        'name' => $data['name'],
                        'type' => $data['type'],
                    ],
                ]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'Failed to create area',
                500
            );
        }
    }

    public function updateArea(Area $area, array $data)
    {
        DB::beginTransaction();

        try {
            $before = $area->toArray();

            $updateData = [
                'name'       => $data['name'],
                'updated_by' => auth()->id(),
            ];

            $area->update($updateData);

            DB::commit();

            $this->logService->log(
                'area.update',
                'Update area',
                200,
                [
                    'before' => $before,
                    'after'  => $area->toArray(),
                ]
            );

            return JsonResponse::success(
                $area,
                'Area updated',
                201
            );

        } catch (Exception $e) {
            DB::rollBack();

            $this->logService->log(
                'area.update',
                'Failed to update area',
                500,
                [
                    'error' => $e->getMessage(),
                    'payload' => $data,
                ]
            );

            throw $e;
        }
    }

    public function deleteArea(Area $area)
    {
        DB::beginTransaction();

        try {

            $area->load('subAreas.levels.notes');

            $logData = [
                'area_id'   => $area->id,
                'area_name' => $area->name,
                'sub_areas' => [],
                'total_sub_areas' => 0,
                'total_levels'    => 0,
                'total_notes'     => 0,
            ];

            $area->update([
                'deleted_by' => auth()->id(),
            ]);

            foreach ($area->subAreas as $subArea) {

                $subArea->update([
                    'deleted_by' => auth()->id(),
                ]);

                $levelData = [];

                foreach ($subArea->levels as $level) {

                    $level->update([
                        'deleted_by' => auth()->id(),
                    ]);

                    $noteData = [];

                    foreach ($level->notes as $note) {

                        $note->update([
                            'deleted_by' => auth()->id(),
                        ]);

                        $noteData[] = [
                            'note_id'   => $note->id,
                            'note_text' => $note->note ?? null,
                        ];

                        $note->delete();
                        $logData['total_notes']++;
                    }

                    $levelData[] = [
                        'level_id' => $level->id,
                        'level_name' => $level->name ?? null,
                        'notes' => $noteData
                    ];

                    $level->delete();
                    $logData['total_levels']++;
                }

                $logData['sub_areas'][] = [
                    'sub_area_id' => $subArea->id,
                    'sub_area_name' => $subArea->name ?? null,
                    'levels' => $levelData
                ];

                $subArea->delete();
                $logData['total_sub_areas']++;
            }

            $remainingAreas = Area::where('type', $area->type)
                ->orderBy('order', 'asc')
                ->get();

            $area->delete();

            $newOrder = 1;

            foreach ($remainingAreas as $item) {
                $item->update([
                    'order' => $newOrder++
                ]);
            }

            DB::commit();

            $this->logService->log(
                'area.delete',
                'Delete area with subareas, levels and notes',
                200,
                $logData
            );

            return JsonResponse::success(
                null,
                'Area deleted successfully',
                200
            );

        } catch (Exception $e) {

            DB::rollBack();

            $this->logService->log(
                'area.delete',
                'Failed to delete area',
                500,
                [
                    'area_id' => $area->id ?? null,
                    'error'   => $e->getMessage(),
                ]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'Failed to delete area',
                500
            );
        }
    }
}
