<?php

namespace App\Services\Assesment;

use App\Helpers\JsonResponse;
use App\Models\Assesment;
use App\Models\BujpProfile;
use App\Models\CategoryAssesment;
use App\Models\LevelAssesment;
use App\Models\QuestionAssesment;
use App\Models\Role;
use App\Models\SignCategoryAssesment;
use App\Models\SignLevelAssesment;
use App\Models\SignQuestionAssesment;
use App\Models\User;
use App\Models\Vendor;
use App\Services\ActivityLog\ActivityLogService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class AssesmentService
{
    protected $logService;

    public function __construct(ActivityLogService $logService)
    {
        $this->logService = $logService;
    }

    public function getAllAssesment(
        $limit = 10,
        $paginate = true,
        $request = null,
        $with = [],
        $symbol = null,
        $send_status = null,
        $unit_id = null
    )
    {
        try {

            $order  = request('order', 'DESC');
            $search = request('q', '');
            $ref    = request('ref', 'id');
            $start  = request('start', null);
            $end    = request('end', null);
            $date  = request('date', null);

            if(null !== $request->query('unit')){
                $vendorId = Crypt::decryptString($request->query('unit'));
                $query = Assesment::where('vendor_id', $vendorId);
            } else {
                $query = Assesment::query();
            }

            if(count($with) > 0){
                $query->with($with);
            }

            if($date != null){
                $query->whereDate('date', $date);
            }

            if($send_status !== null && $symbol !== null){
                $query->where('send_status', $symbol, $send_status);
            }

            if($unit_id !== null){
                $query->where('unit_id', $unit_id);
            }

            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('assesments.contract_number', 'like', "%{$search}%");
                });
            }

            if ($start && $end) {
                $end = date('Y-m-d', strtotime($end . ' +1 day'));
                $query->whereBetween('assesments.created_at', [$start, $end]);

            } elseif ($start) {
                $query->whereDate('assesments.created_at', '>=', $start);

            } elseif ($end) {
                $query->whereDate('assesments.created_at', '<=', $end);
            }

            $query->orderBy($ref, $order);

            if ($paginate) {
                $assesments = $query->paginate($limit)->withQueryString();
            } else {
                $assesments = $limit > 0
                    ? $query->limit($limit)->get()
                    : $query->get();
            }

            return JsonResponse::success($assesments, 'Assesments found', 200);

        } catch (\Exception $e) {

            $this->logService->log(
                'assesment.fetch_all',
                'Failed to fetch assesments',
                500,
                ['error' => $e->getMessage()]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'Assesments not found',
                500
            );
        }
    }

    public function createAssesment(array $data, $encryptedVendorId)
    {
        DB::beginTransaction();

        try {

            $vendorId = Crypt::decryptString($encryptedVendorId);
            $vendor = Vendor::find($vendorId);

            $assesment = Assesment::create([
                'unit_id'   => $vendor->parent_user_id,
                'vendor_id' => $vendor->id,
                'year'      => $data['year'],
                'contract'  => $vendor->contract_number,
                'triwulan'  => $data['triwulan'],
                'send_status' => 0,
                'created_by' => auth()->id(),
            ]);

            $categories = CategoryAssesment::get();

            foreach ($categories as $category) {

                $signCategory = SignCategoryAssesment::create([
                    'vendor_id'              => $vendor->id,
                    'assesment_id'           => $assesment->id,
                    'category_assesment_id'  => $category->id,
                    'category_name'          => $category->name,
                    'created_by'             => auth()->id(),
                ]);

                $questions = QuestionAssesment::where('category_id', $category->id)->get();

                foreach ($questions as $question) {

                    $signQuestion = SignQuestionAssesment::create([
                        'vendor_id'              => $vendor->id,
                        'assesment_id'           => $assesment->id,
                        'question_assesment_id'  => $question->id,
                        'sign_category_id'       => $signCategory->id,
                        'indicator'              => $question->indicator,
                        'created_by'             => auth()->id(),
                    ]);

                    $levels = LevelAssesment::where('question_id', $question->id)->get();

                    foreach ($levels as $level) {

                        SignLevelAssesment::create([
                            'vendor_id'           => $vendor->id,
                            'assesment_id'        => $assesment->id,
                            'level_assesment_id'  => $level->id,
                            'sign_question_id'    => $signQuestion->id,
                            'level'               => $level->level,
                            'level_description'   => $level->level_description,
                            'created_by'          => auth()->id(),
                        ]);
                    }
                }
            }

            DB::commit();

            $this->logService->log(
                'assesment.create',
                'Create assesment success',
                201,
                [
                    'assesment_id' => $assesment->id,
                    'vendor_id'    => $vendor->id,
                ]
            );

            return JsonResponse::success(
                $assesment,
                'Assesment created successfully',
                201
            );

        } catch (Exception $e) {

            DB::rollBack();

            $this->logService->log(
                'assesment.create',
                'Failed to create assesment',
                500,
                [
                    'error'   => $e->getMessage(),
                    'payload' => $data,
                ]
            );
            return JsonResponse::error(
                $e->getMessage(),
                'Failed to create assesment',
                500
            );
        }
    }

    public function updateAssesment(Assesment $assesment, array $data)
    {
        DB::beginTransaction();

        try {
            $before = $assesment->toArray();

            $updateData = [
                'date' => $data['date'] ?? $assesment->date,
                'triwulan' => $data['triwulan'] ?? $assesment->triwulan,
                'updated_by' => auth()->id(),
            ];

            $assesment->update($updateData);

            DB::commit();

            $this->logService->log(
                'assesment.update',
                'Update assesment success',
                200,
                [
                    'before' => $before,
                    'after'  => $assesment->toArray(),
                ]
            );

            return $assesment;

        } catch (Exception $e) {
            DB::rollBack();

            $this->logService->log(
                'assesment.update',
                'Failed to update assesment',
                500,
                [
                    'error' => $e->getMessage(),
                    'payload' => $data,
                ]
            );

            throw $e;
        }
    }

    public function deleteAssesment(Assesment $assesment)
    {
        DB::beginTransaction();

        try {
            SignCategoryAssesment::where('assesment_id',$assesment->id)->update([
                'deleted_by' => auth()->id(),
            ]);
            SignCategoryAssesment::where('assesment_id',$assesment->id)->delete();

            SignQuestionAssesment::where('assesment_id',$assesment->id)->update([
                'deleted_by' => auth()->id(),
            ]);
            SignQuestionAssesment::where('assesment_id',$assesment->id)->delete();

            SignLevelAssesment::where('assesment_id',$assesment->id)->update([
                'deleted_by' => auth()->id(),
            ]);
            SignLevelAssesment::where('assesment_id',$assesment->id)->delete();

            $assesment->update([
                'deleted_by' => auth()->id(),
            ]);

            $assesment->delete();

            DB::commit();

            $this->logService->log(
                'assesment.delete',
                'Delete assesment success',
                200,
                [
                    'assesment_id' => $assesment->id,
                ]
            );

            return JsonResponse::success(
                null,
                'Assesment deleted',
                200
            );

        } catch (Exception $e) {
            DB::rollBack();

            $this->logService->log(
                'assesment.delete',
                'Failed to delete assesment',
                500,
                [
                    'assesment_id' => $assesment->id,
                    'error'   => $e->getMessage(),
                ]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'Failed to delete assesment',
                500
            );
        }
    }

    public function sendAssesment(Assesment $assesment)
    {
        DB::beginTransaction();

        try {            
            $assesment->update([
                'send_status' => 1,
                'send_date' => date('Y-m-d'),
                'updated_by' => auth()->id(),
            ]);

            DB::commit();

            $this->logService->log(
                'assesment.send',
                'Send assesment success',
                200,
                [
                    'assesment_id' => $assesment->id,
                    'date' => $assesment->date,
                    'triwulan' => $assesment->triwulan,
                ]
            );

            return JsonResponse::success(
                $assesment,
                'Assesment sent successfully',
                200
            );

        } catch (\Exception $e) {
            DB::rollBack();

            $this->logService->log(
                'assesment.send',
                'Failed to send assesment',
                500,
                [
                    'assesment_id' => $assesment->id,
                    'error' => $e->getMessage(),
                ]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'Failed to send assesment',
                500
            );
        }
    }

    public function sendAssesmentByUnit(Assesment $assesment)
    {
        DB::beginTransaction();

        try {
            $assesment->update([
                'send_status' => 2,
                'send_date_pusat' => date('Y-m-d'),
                'updated_by' => auth()->id(),
            ]);

            DB::commit();

            $this->logService->log(
                'assesment.send.by_unit',
                'Send assesment by unit success',
                200,
                [
                    'assesment_id' => $assesment->id,
                    'send_date_pusat' => $assesment->send_date_pusat,
                ]
            );

            return JsonResponse::success(
                $assesment,
                'Assesment sent successfully',
                200
            );

        } catch (\Exception $e) {
            DB::rollBack();

            $this->logService->log(
                'assesment.send.by_unit',
                'Failed to send assesment by unit',
                500,
                [
                    'assesment_id' => $assesment->id,
                    'error' => $e->getMessage(),
                ]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'Failed to send assesment by unit',
                500
            );
        }
    }

    public function revisionAssesmentByUnit(Assesment $assesment)
    {
        DB::beginTransaction();

        try {
            $assesment->update([
                'send_status' => 3,
                'updated_by' => auth()->id(),
            ]);

            DB::commit();

            $this->logService->log(
                'assesment.revision.by_unit',
                'Revision assesment by unit success',
                200,
                [
                    'assesment_id' => $assesment->id,
                ]
            );

            return JsonResponse::success(
                $assesment,
                'Assesment revision sent successfully',
                200
            );

        } catch (\Exception $e) {
            DB::rollBack();

            $this->logService->log(
                'assesment.revision.by_unit',
                'Failed to send revision assesment by unit',
                500,
                [
                    'assesment_id' => $assesment->id,
                    'error' => $e->getMessage(),
                ]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'Failed to send revision assesment by unit',
                500
            );
        }
    }

    public function updateQuestion(Request $request, SignQuestionAssesment $signQuestion)
    {
        try {
            DB::beginTransaction();

            $before = $signQuestion->only([
                'level',
                'note',
                'attachment_file'
            ]);

            $questionFile = $signQuestion->attachment_file;

            if ($request->hasFile('attachment_file_' . $signQuestion->id)) {
                $file = $request->file('attachment_file_' . $signQuestion->id);
                $file_name = 'question-file-' . time() . '.' . $file->getClientOriginalExtension();

                if ($signQuestion->attachment_file) {
                    $oldPath = public_path('uploads/attachment_file_question_file/' . $signQuestion->attachment_file);
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }

                $file->move(public_path('uploads/attachment_file_question_file/'), $file_name);
                $questionFile = $file_name;
            }

            $signQuestion->level = $request->input('level_' . $signQuestion->id);
            $signQuestion->note = $request->input('note_' . $signQuestion->id);
            $signQuestion->attachment_file = $questionFile;
            $signQuestion->save();

            $after = $signQuestion->only([
                'level',
                'note',
                'attachment_file'
            ]);

            DB::commit();

            $this->logService->log(
                'assesment.update_question',
                'Update question success',
                200,
                [
                    'question_id' => $signQuestion->id,
                    'assesment_id' => $signQuestion->assesment_id,
                    'sign_category_id' => $signQuestion->sign_category_id,
                    'before' => $before,
                    'after' => $after,
                ]
            );

            return JsonResponse::success(
                $signQuestion,
                'Data berhasil diperbarui',
                200
            );

        } catch (\Throwable $th) {

            DB::rollback();

            $this->logService->log(
                'assesment.update_question',
                'Failed to update question',
                500,
                [
                    'question_id' => $signQuestion->id,
                    'error' => $th->getMessage(),
                ]
            );

            return JsonResponse::error(
                $th->getMessage(),
                'Gagal memperbarui data',
                500
            );
        }
    }

    public function updateQuestionByUnit(Request $request, SignQuestionAssesment $signQuestion)
    {
        try {
            DB::beginTransaction();

            $before = $signQuestion->only([
                'evaluation_unit',
                'note_revision',
            ]);


            $signQuestion->evaluation_unit = $request->input('evaluation_unit_' . $signQuestion->id);
            $note = $request->input('note_revision_' . $signQuestion->id);

            if(!is_null($note)){
                $signQuestion->note_revision = $note;
            }
            
            $signQuestion->save();


            $after = $signQuestion->only([
                'evaluation_unit',
                'note_revision',
            ]);

            DB::commit();

            $this->logService->log(
                'assesment.update_question_by_unit',
                'Update question success',
                200,
                [
                    'question_id' => $signQuestion->id,
                    'assesment_id' => $signQuestion->assesment_id,
                    'sign_category_id' => $signQuestion->sign_category_id,
                    'before' => $before,
                    'after' => $after,
                ]
            );

            return JsonResponse::success(
                $signQuestion,
                'Data berhasil diperbarui',
                200
            );

        } catch (\Throwable $th) {

            DB::rollback();

            $this->logService->log(
                'assesment.update_question_by_unit',
                'Failed to update question',
                500,
                [
                    'question_id' => $signQuestion->id,
                    'error' => $th->getMessage(),
                ]
            );

            return JsonResponse::error(
                $th->getMessage(),
                'Gagal memperbarui data',
                500
            );
        }
    }

    public function revisionQuestionByUnit(Request $request, SignQuestionAssesment $signQuestion)
    {
        try {
            DB::beginTransaction();

            $before = $signQuestion->only([
                'note_revision',
            ]);


            $signQuestion->note_revision = $request->input('note_revision_' . $signQuestion->id);
            $signQuestion->save();


            $after = $signQuestion->only([
                'note_revision',
            ]);

            DB::commit();

            $this->logService->log(
                'assesment.revision_question_by_unit',
                'Revision question success',
                200,
                [
                    'question_id' => $signQuestion->id,
                    'assesment_id' => $signQuestion->assesment_id,
                    'sign_category_id' => $signQuestion->sign_category_id,
                    'before' => $before,
                    'after' => $after,
                ]
            );

            return JsonResponse::success(
                $signQuestion,
                'Data berhasil diperbarui',
                200
            );

        } catch (\Throwable $th) {

            DB::rollback();

            $this->logService->log(
                'assesment.revision_question_by_unit',
                'Failed to update question',
                500,
                [
                    'question_id' => $signQuestion->id,
                    'error' => $th->getMessage(),
                ]
            );

            return JsonResponse::error(
                $th->getMessage(),
                'Gagal memperbarui data',
                500
            );
        }
    }

    public function getReportAssesment(Assesment $assesment, $avg = 'evaluation_unit')
    {
        try {
            $assesment->load('vendor', 'unit', 'bujpProfile');
            $categories = SignCategoryAssesment::with([
                    'questions',
                    'questions.levels'
                ])
                ->where('assesment_id', $assesment->id)
                ->get()
                ->map(function ($category) use ($avg) {

                    $category->average = number_format(
                        $category->questions->avg($avg),
                        2
                    );

                    return $category;
                });

            $chartData = [
                'labels' => $categories->pluck('category_name'),
                'data'   => $categories->pluck('average'),
            ];

            return JsonResponse::success([
                'assesment' => $assesment,
                'categories' => $categories,
                'chart' => $chartData
            ], 'Report assesment berhasil diambil', 200);

        } catch (\Exception $e) {

            $this->logService->log(
                'assesment.report',
                'Failed to fetch report assesment',
                500,
                [
                    'assesment_id' => $assesment->id,
                    'error' => $e->getMessage()
                ]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'Gagal mengambil report assesment',
                500
            );
        }
    }
}
