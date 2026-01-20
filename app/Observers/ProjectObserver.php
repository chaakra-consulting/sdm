<?php

namespace App\Observers;

use App\Models\ProjectCRM;
use Carbon\Carbon;
use App\Models\ProjectPerusahaan;

class ProjectObserver
{
    /**
     * Handle the ProjectPerusahaan "created" event.
     */
    public function created(ProjectPerusahaan $projectPerusahaan): void
    {
        //
    }

    /**
     * Handle the ProjectPerusahaan "updated" event.
     */
    public function updating(ProjectPerusahaan $projectPerusahaan): void
    {
        $projectPerusahaan->progres = $projectPerusahaan->calculateProgress();
        $ref_bukukas = $projectPerusahaan->ref_bukukas_id;
        if ($projectPerusahaan->progres >= 100) {
            $projectPerusahaan->status = 'selesai';
            ProjectCRM::where('project_bukukas_id', '=', $ref_bukukas)->update(
                [
                    'status' => 'selesai',
                ]
            );
        } else if ($projectPerusahaan->deadline && Carbon::parse($projectPerusahaan->deadline)->isPast()) {
            $projectPerusahaan->status = 'telat';
            ProjectCRM::where('project_bukukas_id', '=', $ref_bukukas)->update(
                [
                    'status' => 'telat',
                ]
            );
        } else if ($projectPerusahaan->waktu_mulai && Carbon::parse($projectPerusahaan->waktu_mulai)->isPast()) {
            $projectPerusahaan->status = 'proses';
            ProjectCRM::where('project_bukukas_id', '=', $ref_bukukas)->update(
                [
                    'status' => 'proses',
                ]
            );
        }
    }

    /**
     * Handle the ProjectPerusahaan "deleted" event.
     */
    public function deleted(ProjectPerusahaan $projectPerusahaan): void
    {
        //
    }

    /**
     * Handle the ProjectPerusahaan "restored" event.
     */
    public function restored(ProjectPerusahaan $projectPerusahaan): void
    {
        //
    }

    /**
     * Handle the ProjectPerusahaan "force deleted" event.
     */
    public function forceDeleted(ProjectPerusahaan $projectPerusahaan): void
    {
        //
    }
}
