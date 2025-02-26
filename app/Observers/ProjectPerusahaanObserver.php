<?php

namespace App\Observers;

use Carbon\Carbon;
use App\Models\ProjectPerusahaan;
use App\Models\ProjectPerusahaanEksternal;

class ProjectPerusahaanObserver
{
    /**
     * Handle the ProjectPerusahaan "created" event.
     */
    public function created(ProjectPerusahaan $projectPerusahaan): void
    {
        $deadline = Carbon::parse($projectPerusahaan->deadline);

        ProjectPerusahaanEksternal::create([
            'tahun' => $deadline->year,
            'nama' => $projectPerusahaan->nama_project,
            'create_at' => now(),
        ]);
    }

    /**
     * Handle the ProjectPerusahaan "updated" event.
     */
    public function updated(ProjectPerusahaan $projectPerusahaan): void
    {
        //
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
