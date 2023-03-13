<?php

namespace App\Observers;

use App\Models\Marker;

class MarkerObserver
{
    /**
     * Handle the Marker "created" event.
     */
    public function created(Marker $marker): void
    {
        //
    }

    /**
     * Handle the Marker "updated" event.
     */
    public function updated(Marker $marker): void
    {
        //
    }

    /**
     * Handle the Marker "deleted" event.
     */
    public function deleted(Marker $marker): void
    {
        $marker->markerMedia->each(function ($markerMedia) {
            $markerMedia->delete();
        });
    }

    /**
     * Handle the Marker "restored" event.
     */
    public function restored(Marker $marker): void
    {
        //
    }

    /**
     * Handle the Marker "force deleted" event.
     */
    public function forceDeleted(Marker $marker): void
    {
        //
    }
}
