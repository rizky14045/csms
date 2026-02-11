<?php

namespace App\Services\QuestionAssesment;

use App\Helpers\JsonResponse;
use App\Models\QuestionAssesment;
use App\Services\ActivityLog\ActivityLogService;
use Exception;
use Illuminate\Support\Facades\DB;

class QuestionAssesmentService
{
    protected $logService;

    public function __construct(ActivityLogService $logService)
    {
        $this->logService = $logService;
    }

    public function createQuestionAssesment(array $data, $category)
    {
        DB::beginTransaction();

        try {
             $lastQuestion = QuestionAssesment::latest()->first();
            $order = $lastQuestion ? $lastQuestion->order + 1 : 1; 

            $lastQuestion = QuestionAssesment::where('category_id', $category->id)->latest()->first();
            $order = $lastQuestion ? $lastQuestion->order + 1 : 1; 

            $question = QuestionAssesment::create([
                'category_id' => $category->id,
                'indicator' => $data['indicator'],
                'order' => $order,
                'created_by' => auth()->id(),
            ]);
            
            DB::commit();

            $this->logService->log(
                'question-assesment.create',
                'Create question assesment',
                201,
                [
                    'category_id' => $question->category_id,
                    'indicator'   => $question->indicator,
                    'order'    => $question->order,
                ]
            );

            return JsonResponse::success(
                $question,
                'Question Assesment created',
                201
            );
        } catch (Exception $e) {
            DB::rollBack();

            $this->logService->log(
                'question-assesment.create',
                'Failed to create question assesment',
                500,
                [
                    'error' => $e->getMessage(),
                    'payload' => [
                        'category_id' => $category->id ?? null,
                        'indicator' => $data['indicator'] ?? null,
                    ],
                ]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'Failed to create question assesment',
                500
            );
        }
    }

    public function updateQuestionAssesment(QuestionAssesment $question, array $data)
    {
        DB::beginTransaction();

        try {
            $before = $question->toArray();

            $updateData = [
                'indicator'       => $data['indicator'],
                'updated_by' => auth()->id(),
            ];

            $question->update($updateData);

            DB::commit();

            $this->logService->log(
                'question-assesment.update',
                'Update question assesment',
                200,
                [
                    'before' => $before,
                    'after'  => $question->toArray(),
                ]
            );

            return JsonResponse::success(
                $question,
                'Question Assesment updated',
                201
            );

        } catch (Exception $e) {
            DB::rollBack();

            $this->logService->log(
                'question-assesment.update',
                'Failed to update question assesment',
                500,
                [
                    'error' => $e->getMessage(),
                    'payload' => $data,
                ]
            );

            throw $e;
        }
    }

    public function deleteQuestionAssesment(QuestionAssesment $questionAssesment)
    {
        DB::beginTransaction();

        try {

            $categoryId = $questionAssesment->category_id;

            $levels = $questionAssesment->levels()->get();

            $logData = [
                'question_id' => $questionAssesment->id,
                'category_id' => $categoryId,
                'question_indicator' => $questionAssesment->indicator,
                'levels' => [],
                'total_levels' => 0,
            ];

            $questionAssesment->update([
                'deleted_by' => auth()->id(),
            ]);

            foreach ($levels as $level) {

                $level->update([
                    'deleted_by' => auth()->id(),
                ]);

                $level->delete();

                $logData['levels'][] = [
                    'level_id' => $level->id,
                    'level_name' => $level->name ?? null,
                ];

                $logData['total_levels']++;
            }

            $questionAssesment->delete();

            $remainingQuestions = QuestionAssesment::where('category_id', $categoryId)
                ->orderBy('order', 'asc')
                ->get();

            $newOrder = 1;

            foreach ($remainingQuestions as $question) {
                $question->update([
                    'order' => $newOrder++
                ]);
            }

            DB::commit();

            $this->logService->log(
                'question-assesment.delete',
                'Delete question assesment and reorder',
                201,
                $logData
            );

            return JsonResponse::success(
                null,
                'Question Assesment deleted',
                201
            );

        } catch (Exception $e) {

            DB::rollBack();

            $this->logService->log(
                'question-assesment.delete',
                'Failed to delete question assesment',
                500,
                [
                    'question_id' => $questionAssesment->id ?? null,
                    'error' => $e->getMessage(),
                ]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'Failed to delete question assesment',
                500
            );
        }
    }
}
