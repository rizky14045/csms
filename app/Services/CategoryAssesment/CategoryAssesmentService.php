<?php

namespace App\Services\CategoryAssesment;

use App\Helpers\JsonResponse;
use App\Models\CategoryAssesment;
use App\Services\ActivityLog\ActivityLogService;
use Exception;
use Illuminate\Support\Facades\DB;

class CategoryAssesmentService
{
    protected $logService;

    public function __construct(ActivityLogService $logService)
    {
        $this->logService = $logService;
    }
    
   public function getAllCategoryAssesment($limit = 10, $paginate = true, $with = [])
    {
        try {
            $order  = request('order', 'ASC');
            $search = request('q', '');
            $ref    = request('ref', 'order');
            $start  = request('start', null);
            $end    = request('end', null);

            $query = CategoryAssesment::query();

            if (!empty($with)) {
                $query->with($with);
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

            return JsonResponse::success($data, 'Category Assesment found', 200);

        } catch (Exception $e) {

            $this->logService->log(
                'category-assesment.fetch_all',
                'Failed to fetch category assesments',
                500,
                [
                    'error' => $e->getMessage(),
                    'params' => request()->all(),
                ]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'Category Assesment not found',
                500
            );
        }
    }

    public function createCategoryAssesment(array $data)
    {
        DB::beginTransaction();

        try {
             $lastCategory = CategoryAssesment::latest()->first();
            $order = $lastCategory ? $lastCategory->order + 1 : 1; 

            $category = CategoryAssesment::create([
                'name' => $data['name'],
                'order' => $order,
                'created_by' => auth()->id(),
            ]);
            
            DB::commit();

            $this->logService->log(
                'category-assesment.create',
                'Create category assesment',
                201,
                [
                    'category_id' => $category->id,
                    'name'   => $category->name,
                    'order'    => $category->order,
                ]
            );

            return JsonResponse::success(
                $category,
                'Category Assesment created',
                201
            );
        } catch (Exception $e) {
            DB::rollBack();

            $this->logService->log(
                'category-assesment.create',
                'Failed to create category assesment',
                500,
                [
                    'error' => $e->getMessage(),
                    'payload' => [
                        'incident' => $data['incident'] ?? null,
                        'type' => $data['type'] ?? null,
                    ],
                ]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'Failed to create vulnerability',
                500
            );
        }
    }

    public function updateCategoryAssesment(CategoryAssesment $category, array $data)
    {
        DB::beginTransaction();

        try {
            $before = $category->toArray();

            $updateData = [
                'name'       => $data['name'],
                'updated_by' => auth()->id(),
            ];

            $category->update($updateData);

            DB::commit();

            $this->logService->log(
                'category-assesment.update',
                'Update category assesment',
                200,
                [
                    'before' => $before,
                    'after'  => $category->toArray(),
                ]
            );

            return JsonResponse::success(
                $category,
                'Category Assesment updated',
                201
            );

        } catch (Exception $e) {
            DB::rollBack();

            $this->logService->log(
                'category-assesment.update',
                'Failed to update category assesment',
                500,
                [
                    'error' => $e->getMessage(),
                    'payload' => $data,
                ]
            );

            throw $e;
        }
    }

    public function deleteCategoryAssesment(CategoryAssesment $categoryAssesment)
    {
        DB::beginTransaction();

        try {

            $categoryId = $categoryAssesment->id;

            $questions = $categoryAssesment->questions()
                ->with('levels')
                ->get();

            $logData = [
                'category_id' => $categoryId,
                'category_name' => $categoryAssesment->name,
                'questions' => [],
                'total_questions' => 0,
                'total_levels' => 0,
            ];

            $categoryAssesment->update([
                'deleted_by' => auth()->id(),
            ]);

            foreach ($questions as $question) {

                $question->update([
                    'deleted_by' => auth()->id(),
                ]);

                $levelData = [];

                foreach ($question->levels as $level) {

                    $level->update([
                        'deleted_by' => auth()->id(),
                    ]);

                    $levelData[] = [
                        'level_id' => $level->id,
                        'level_name' => $level->name ?? null,
                    ];

                    $level->delete();
                    $logData['total_levels']++;
                }

                $question->delete();

                $logData['questions'][] = [
                    'question_id' => $question->id,
                    'question_text' => $question->question ?? null,
                    'levels' => $levelData
                ];

                $logData['total_questions']++;
            }

            $categoryAssesment->delete();

            $remainingCategories = CategoryAssesment::orderBy('order', 'asc')
                ->get();

            $newOrder = 1;

            foreach ($remainingCategories as $category) {
                $category->update([
                    'order' => $newOrder++
                ]);
            }

            DB::commit();

            $this->logService->log(
                'category-assesment.delete',
                'Delete category assesment with children and reorder',
                201,
                $logData
            );

            return JsonResponse::success(
                null,
                'Category Assesment deleted',
                201
            );

        } catch (Exception $e) {

            DB::rollBack();

            $this->logService->log(
                'category-assesment.delete',
                'Failed to delete category assesment',
                500,
                [
                    'category_id' => $categoryAssesment->id ?? null,
                    'error' => $e->getMessage(),
                ]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'Failed to delete category assesment',
                500
            );
        }
    }
}
