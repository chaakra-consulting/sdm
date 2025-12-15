<?php

namespace App\Listeners;

use App\Events\SubtaskStatusChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateTaskStatus
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(SubtaskStatusChanged $event): void
    {
        $task = $event->task;
        if (!$task) return;
        $task->refresh();
        $totalSubtask = $task->sub_task()->count();
        $approvedSubtask = $task->sub_task()->where('status', 'approve')->count();

        if ($totalSubtask > 0 && $approvedSubtask === $totalSubtask) {
            if ($task->status !== 'selesai') {
                $task->update([
                    'status' => 'selesai',
                    'tgl_selesai' => now()
                ]);
            }
        } else {
            if ($task->status === 'selesai') {
                $task->update([
                    'status' => 'proses',
                    'tgl_selesai' => null
                ]);
            } elseif ($task->status === 'belum' && $approvedSubtask > 0) {
                $task->update(['status' => 'proses']);
            }
        }

        $project = $task->project_perusahaan;
        if ($project && method_exists($project, 'calculateProgress')) {
            $project->update(['progres' => $project->calculateProgress()]);
        }
    }
}