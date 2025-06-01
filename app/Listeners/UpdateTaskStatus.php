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
        $totalSubtask = $task->sub_task()->count();
        $approvedSubtask = $task->sub_task()->where('status', 'approve')->count();

        if ($totalSubtask > 0 && $approvedSubtask === $totalSubtask) {
            $task->update([
                'status' => 'selesai',
                'tgl_selesai' => now()
            ]);
        }
    }
}
