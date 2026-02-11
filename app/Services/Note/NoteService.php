<?php

namespace App\Services\Note;

use App\Helpers\JsonResponse;
use App\Models\Level;
use App\Models\Note;
use App\Services\ActivityLog\ActivityLogService;
use Exception;
use Illuminate\Support\Facades\DB;

class NoteService
{
    protected $logService;

    public function __construct(ActivityLogService $logService)
    {
        $this->logService = $logService;
    }

    public function createNote(array $data, Level $level)
    {
        DB::beginTransaction();

        try {
            $lastNote = Note::where('type', $data['type'])->latest()->first();
            $order = $lastNote ? $lastNote->order + 1 : 1; 
            $note = Note::create([
                'level_id' => $level->id,
                'note' => $data['note'],
                'order' => $order,
                'type' => $data['type'],
                'created_by' => auth()->id(),
            ]);

            DB::commit();

            $this->logService->log(
                'note.create',
                'Create note',
                201,
                [
                    'level_id' => $level->id,
                    'note'   => $data['note'],
                    'order'    => $order,
                ]
            );

            return JsonResponse::success(
                $note,
                'Note created',
                201
            );
        } catch (Exception $e) {
            DB::rollBack();

            $this->logService->log(
                'note.create',
                'Failed to create note',
                500,
                [
                    'error' => $e->getMessage(),
                    'payload' => [
                        'level_id' => $level->id,
                        'note'   => $data['note'],
                    ],
                ]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'Failed to create note',
                500
            );
        }
    }

    public function updateNote(Note $note, array $data)
    {
        DB::beginTransaction();

        try {
            $before = $note->toArray();

            $updateData = [
                'note' => $data['note'],
                'updated_by' => auth()->id(),
            ];

            $note->update($updateData);

            DB::commit();

            $this->logService->log(
                'note.update',
                'Update note',
                201,
                [
                    'before' => $before,
                    'after'  => $note->toArray(),
                ]
            );

            return JsonResponse::success(
                $note,
                'Note updated',
                201
            );

        } catch (Exception $e) {
            DB::rollBack();

            $this->logService->log(
                'note.update',
                'Failed to update note',
                500,
                [
                    'error' => $e->getMessage(),
                    'payload' => $data,
                ]
            );

            throw $e;
        }
    }

    public function deleteNote(Note $note)
    {
        DB::beginTransaction();

        try {

            $levelId      = $note->level_id;
            $deletedOrder = $note->order;

            $logData = [
                'note_id'   => $note->id,
                'note_text' => $note->note ?? null,
                'level_id'  => $levelId,
                'order'     => $deletedOrder,
            ];

            $note->update([
                'deleted_by' => auth()->id(),
            ]);

            $note->delete();

            $remainingNotes = Note::where('level_id', $levelId)
                ->orderBy('order', 'asc')
                ->get();

            $newOrder = 1;

            foreach ($remainingNotes as $item) {
                $item->update([
                    'order' => $newOrder++
                ]);
            }

            DB::commit();

            $this->logService->log(
                'note.delete',
                'Delete note and reorder',
                200,
                $logData
            );

            return JsonResponse::success(
                null,
                'Note deleted successfully',
                200
            );

        } catch (Exception $e) {

            DB::rollBack();

            $this->logService->log(
                'note.delete',
                'Failed to delete note',
                500,
                [
                    'note_id' => $note->id ?? null,
                    'error'   => $e->getMessage(),
                ]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'Failed to delete note',
                500
            );
        }
    }


}
