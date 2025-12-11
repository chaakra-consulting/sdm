<?php

namespace App\Observers;

use App\Events\SubtaskStatusChanged;
use App\Models\DetailSubTask;
use Illuminate\Support\Facades\Log;

class DetailSubTaskObserver
{
    /**
     * Handle the DetailSubTask "created" event.
     */
    public function created(DetailSubTask $detailSubTask): void
    {
        //
    }

    /**
     * Handle the DetailSubTask "updated" event.
     */
    public function updated(DetailSubTask $detailSubTask): void
    {
        if ($detailSubTask->isDirty('status')) {
            $subTask = $detailSubTask->subtask;

            if ($subTask) {
                $totalDetail = $subTask->detail_sub_task()->count();
                $approvedCount = $subTask->detail_sub_task()->where('status', 'approved')->count();
                
                Log::info("Observer Run: SubTask ID {$subTask->id}. Total: $totalDetail, $approvedCount");

                if ($totalDetail > 0 && $totalDetail === $approvedCount) {
                    $subTask->update([
                        'status' => 'approve',
                        'tgl_selesai' => now()
                    ]);
                } else {
                    $subTask->update([
                        'status' => null,
                        'tgl_selesai' => null
                    ]);
                }

                if ($subTask->task) {
                    SubtaskStatusChanged::dispatch($subTask->task);
                }
            }
        }
    }

    /**
     * Handle the DetailSubTask "deleted" event.
     */
    public function deleted(DetailSubTask $detailSubTask): void
    {
        //
    }

    /**
     * Handle the DetailSubTask "restored" event.
     */
    public function restored(DetailSubTask $detailSubTask): void
    {
        //
    }

    /**
     * Handle the DetailSubTask "force deleted" event.
     */
    public function forceDeleted(DetailSubTask $detailSubTask): void
    {
        //
    }
}
