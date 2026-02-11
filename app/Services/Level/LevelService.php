<?php

namespace App\Services\Level;

use App\Helpers\JsonResponse;
use App\Models\Area;
use App\Models\QuestionAssesment;
use App\Models\Level;
use App\Models\SubArea;
use App\Services\ActivityLog\ActivityLogService;
use Exception;
use Illuminate\Support\Facades\DB;

class LevelService
{
    protected $logService;

    public function __construct(ActivityLogService $logService)
    {
        $this->logService = $logService;
    }

    public function createLevel(array $data, SubArea $subArea)
    {
        DB::beginTransaction();

        try {
            $lastLevel = Level::where('type',$data['type'])->latest()->first();
            $order = $lastLevel ? $lastLevel->order + 1 : 1; 
            Level::create([
                'sub_area_id' => $subArea->id,
                'level' => $data['level'],
                'description' => $data['description'],
                'order' => $order,
                'type' =>$data['type'],
                'created_by' => auth()->id(),
            ]);

            DB::commit();

            $this->logService->log(
                'level.create',
                'Create level',
                201,
                [
                    'sub_area_id' => $subArea->id,
                    'level'   => $data['level'],
                    'order'    => $order,
                ]
            );

            return JsonResponse::success(
                $subArea,
                'Level created',
                201
            );
        } catch (Exception $e) {
            DB::rollBack();

            $this->logService->log(
                'level.create',
                'Failed to create level',
                500,
                [
                    'error' => $e->getMessage(),
                    'payload' => [
                        'sub_area_id' => $subArea->id,
                        'level'   => $data['level'],
                        'description' => $data['description'],
                    ],
                ]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'Failed to create level',
                500
            );
        }
    }

    public function updateLevel(Level $level, array $data)
    {
        DB::beginTransaction();

        try {
            $before = $level->toArray();

            $updateData = [
                'level' => $data['level'],
                'description' => $data['description'],
                'updated_by' => auth()->id(),
            ];

            $level->update($updateData);

            DB::commit();

            $this->logService->log(
                'level.update',
                'Update level',
                201,
                [
                    'before' => $before,
                    'after'  => $level->toArray(),
                ]
            );

            return JsonResponse::success(
                $level,
                'Level updated',
                201
            );

        } catch (Exception $e) {
            DB::rollBack();

            $this->logService->log(
                'level.update',
                'Failed to update level',
                500,
                [
                    'error' => $e->getMessage(),
                    'payload' => $data,
                ]
            );

            throw $e;
        }
    }

    public function deleteLevel(Level $level)
    {
        DB::beginTransaction();

        try {

            $subAreaId   = $level->sub_area_id;
            $deletedOrder = $level->order;

            $level->load('notes');

            $logData = [
                'level_id'    => $level->id,
                'level_name'  => $level->name ?? null,
                'sub_area_id' => $subAreaId,
                'order'       => $deletedOrder,
                'notes'       => [],
                'total_notes' => 0,
            ];

            $level->update([
                'deleted_by' => auth()->id(),
            ]);

            foreach ($level->notes as $note) {

                $note->update([
                    'deleted_by' => auth()->id(),
                ]);

                $logData['notes'][] = [
                    'note_id'   => $note->id,
                    'note_text' => $note->note ?? null,
                ];

                $note->delete();
                $logData['total_notes']++;
            }

            $level->delete();

             $remainingSubAreas = Level::where('sub_area_id', $subAreaId)
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
                'level.delete',
                'Delete level with notes and reorder',
                200,
                $logData
            );

            return JsonResponse::success(
                null,
                'Level deleted successfully',
                200
            );

        } catch (Exception $e) {

            DB::rollBack();

            $this->logService->log(
                'level.delete',
                'Failed to delete level',
                500,
                [
                    'level_id' => $level->id ?? null,
                    'error'    => $e->getMessage(),
                ]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'Failed to delete level',
                500
            );
        }
    }

}
