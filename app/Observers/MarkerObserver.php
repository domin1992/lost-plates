<?php

namespace App\Observers;

use App\Models\Marker;

class MarkerObserver
{
    /**
     * Handle the Makrer "created" event.
     */
    public function created(Marker $marker): void
    {
        //
    }

    /**
     * Handle the Makrer "updated" event.
     */
    public function updated(Marker $marker): void
    {
        //
    }

    /**
     * Handle the Makrer "deleted" event.
     */
    public function deleted(Marker $marker): void
    {
        $marker->markerMedia->each(function ($markerMedia) {
            $markerMedia->delete();
        });
    }

    /**
     * Handle the Makrer "restored" event.
     */
    public function restored(Marker $marker): void
    {
        //
    }

    /**
     * Handle the Makrer "force deleted" event.
     */
    public function forceDeleted(Marker $marker): void
    {
        //
    }
}
