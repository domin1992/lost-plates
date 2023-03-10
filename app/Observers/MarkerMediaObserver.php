<?php

namespace App\Observers;

use App\Models\MarkerMedia;

class MarkerMediaObserver
{
    /**
     * Handle the MarkerMedia "created" event.
     */
    public function created(MarkerMedia $markerMedia): void
    {
        //
    }

    /**
     * Handle the MarkerMedia "updated" event.
     */
    public function updated(MarkerMedia $markerMedia): void
    {
        //
    }

    /**
     * Handle the MarkerMedia "deleted" event.
     */
    public function deleted(MarkerMedia $markerMedia): void
    {
        $markerMedia->media->delete();
    }

    /**
     * Handle the MarkerMedia "restored" event.
     */
    public function restored(MarkerMedia $markerMedia): void
    {
        //
    }

    /**
     * Handle the MarkerMedia "force deleted" event.
     */
    public function forceDeleted(MarkerMedia $markerMedia): void
    {
        //
    }
}
