<?php

namespace App\Services\LevelAssesment;

use App\Helpers\JsonResponse;
use App\Models\LevelAssesment;
use App\Models\QuestionAssesment;
use App\Services\ActivityLog\ActivityLogService;
use Exception;
use Illuminate\Support\Facades\DB;

class LevelAssesmentService
{
    protected $logService;

    public function __construct(ActivityLogService $logService)
    {
        $this->logService = $logService;
    }

    public function createLevelAssesment(array $data, QuestionAssesment $question)
    {
        DB::beginTransaction();

        try {
            $lastLevel = LevelAssesment::where('question_id',$question->id)->latest()->first();
            $order = $lastLevel ? $lastLevel->order + 1 : 1; 
            
            $level = LevelAssesment::create([
                'question_id' => $question->id,
                'level' => $data['level'],
                'level_description' => $data['level_description'],
                'order' => $order,
                'created_by' => auth()->id(),
            ]);
            
            DB::commit();

            $this->logService->log(
                'level-assesment.create',
                'Create level assesment',
                201,
                [
                    'level_id' => $level->id,
                    'question_id' => $question->id,
                    'level' => $data['level'],
                    'order' => $order,
                ]
            );

            return JsonResponse::success(
                $level,
                'Level Assesment created',
                201
            );
        } catch (Exception $e) {
            DB::rollBack();

            $this->logService->log(
                'level-assesment.create',
                'Failed to create level assesment',
                500,
                [
                    'error' => $e->getMessage(),
                    'payload' => [
                        'question_id' => $question->id ?? null,
                        'level' => $data['level'] ?? null,
                    ],
                ]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'Failed to create level assesment',
                500
            );
        }
    }

    public function updateLevelAssesment(LevelAssesment $level, array $data)
    {
        DB::beginTransaction();

        try {
            $before = $level->toArray();

            $updateData = [
                'level' => $data['level'],
                'level_description' => $data['level_description'],
                'updated_by' => auth()->id(),
            ];

            $level->update($updateData);

            DB::commit();

            $this->logService->log(
                'level-assesment.update',
                'Update level assesment',
                200,
                [
                    'before' => $before,
                    'after'  => $level->toArray(),
                ]
            );

            return JsonResponse::success(
                $level,
                'Level Assesment updated',
                201
            );

        } catch (Exception $e) {
            DB::rollBack();

            $this->logService->log(
                'level-assesment.update',
                'Failed to update level assesment',
                500,
                [
                    'error' => $e->getMessage(),
                    'payload' => $data,
                ]
            );

            throw $e;
        }
    }

    public function deleteLevelAssesment(LevelAssesment $levelAssesment)
    {
        DB::beginTransaction();

        try {
            $questionID = $levelAssesment->question_id;
            $levelAssesment->update([
                'deleted_by' => auth()->id(),
            ]);

            $levelAssesment->delete();

            // Reorder remaining levels
            $remainingLevels = LevelAssesment::where('question_id', $questionID)
                ->orderBy('order')
                ->get();
            
            $newOrder = 1;
            foreach ($remainingLevels as $index => $level) {
                $level->update(['order' => $newOrder++]);
            }

            DB::commit();

            $this->logService->log(
                'level-assesment.delete',
                'Delete level assesment',
                201,
                [
                    'level_id' => $levelAssesment->id,
                    'level' => $levelAssesment->level,
                    'level_description'   => $levelAssesment->level_description,
                ]
            );

            return JsonResponse::success(
                null,
                'Level Assesment deleted',
                201
            );

        } catch (Exception $e) {

            DB::rollBack();

            $this->logService->log(
                'level-assesment.delete',
                'Failed to delete level assesment',
                500,
                [
                    'level_id' => $levelAssesment->id ?? null,
                    'error' => $e->getMessage(),
                ]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'Failed to delete level assesment',
                500
            );
        }
    }
}
