<?php

namespace App\Models;

use App\Models\Traits\Uuids;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Zencoreitservices\MediaManager\Models\Media as Model;

class Media extends Model
{
    use HasFactory, Uuids;

    /**
     * Get the prunable model query.
     */
    public function prunable(): Builder
    {
        return static::select('media.*')
            ->whereNotExists(function ($query) {
                $query->selectRaw(1)
                    ->from('marker_media')
                    ->whereRaw('marker_media.media_id = media.id');
            })
            ->where('created_at', '<=', now()->subDay());
    }
}
