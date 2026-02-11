<?php

namespace App\Services\SubArea;

use App\Helpers\JsonResponse;
use App\Models\Area;
use App\Models\SubArea;
use App\Services\ActivityLog\ActivityLogService;
use Exception;
use Illuminate\Support\Facades\DB;

class SubAreaService
{
    protected $logService;

    public function __construct(ActivityLogService $logService)
    {
        $this->logService = $logService;
    }

    public function createSubArea(array $data, Area $area)
    {
        DB::beginTransaction();

        try {
            $lastSubArea = SubArea::where('type',$data['type'])->latest()->first();
            $order = $lastSubArea ? $lastSubArea->order + 1 : 1; 
            $subarea = SubArea::create([
                'area_id' => $area->id,
                'name' => $data['name'],
                'description' => $data['description'],
                'reference' => $data['reference'],
                'order' => $order,
                'type' =>$data['type'],
                'created_by' => auth()->id(),
            ]);
            
            DB::commit();

            $this->logService->log(
                'subarea.create',
                'Create subarea',
                201,
                [
                    'area_id' => $subarea->area_id,
                    'name'   => $subarea->name,
                    'order'    => $subarea->order,
                ]
            );

            return JsonResponse::success(
                $subarea,
                'Sub Area created',
                201
            );
        } catch (Exception $e) {
            DB::rollBack();

            $this->logService->log(
                'subarea.create',
                'Failed to create subarea',
                500,
                [
                    'error' => $e->getMessage(),
                    'payload' => [
                        'area_id' => $area->id ?? null,
                        'name' => $data['name'] ?? null,
                        'description' => $data['description'] ?? null,
                        'reference' => $data['reference'] ?? null,
                    ],
                ]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'Failed to create subarea',
                500
            );
        }
    }

    public function updateSubArea(SubArea $subArea, array $data)
    {
        DB::beginTransaction();

        try {
            $before = $subArea->toArray();

            $updateData = [
                'name'       => $data['name'],
                'description' => $data['description'],
                'reference' => $data['reference'],
                'updated_by' => auth()->id(),
            ];

            $subArea->update($updateData);

            DB::commit();

            $this->logService->log(
                'subarea.update',
                'Update subarea',
                201,
                [
                    'before' => $before,
                    'after'  => $subArea->toArray(),
                ]
            );

            return JsonResponse::success(
                $subArea,
                'Sub Area updated',
                201
            );

        } catch (Exception $e) {
            DB::rollBack();

            $this->logService->log(
                'subarea.update',
                'Failed to update subarea',
                500,
                [
                    'error' => $e->getMessage(),
                    'payload' => $data,
                ]
            );

            throw $e;
        }
    }

    public function deleteSubArea(SubArea $subArea)
    {
        DB::beginTransaction();

        try {

            $areaId = $subArea->area_id;

            $subArea->load('levels.notes');

            $logData = [
                'sub_area_id'   => $subArea->id,
                'sub_area_name' => $subArea->name ?? null,
                'area_id'       => $areaId,
                'levels'        => [],
                'total_levels'  => 0,
                'total_notes'   => 0,
            ];

            $subArea->update([
                'deleted_by' => auth()->id(),
            ]);

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
                        'note_id' => $note->id,
                        'note_text' => $note->note ?? null,
                    ];

                    $note->delete();
                    $logData['total_notes']++;
                }

                $logData['levels'][] = [
                    'level_id' => $level->id,
                    'level_name' => $level->name ?? null,
                    'notes' => $noteData,
                ];

                $level->delete();
                $logData['total_levels']++;
            }

            $subArea->delete();

            $remainingSubAreas = SubArea::where('area_id', $areaId)
                ->orderBy('order', 'asc')
                ->get();

            $newOrder = 1;

            foreach ($remainingSubAreas as $item) {
                $item->update([
                    'order' => $newOrder++
                ]);
            }

            DB::commit();

            $this->logService->log(
                'subarea.delete',
                'Delete subarea with levels and notes then reorder',
                201,
                $logData
            );

            return JsonResponse::success(
                null,
                'SubArea deleted successfully',
                201
            );

        } catch (Exception $e) {

            DB::rollBack();

            $this->logService->log(
                'subarea.delete',
                'Failed to delete subarea',
                500,
                [
                    'sub_area_id' => $subArea->id ?? null,
                    'error' => $e->getMessage(),
                ]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'Failed to delete subarea',
                500
            );
        }
    }
}
