<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarkerMedia extends Model
{
    protected $fillable = [
        'marker_id',
        'media_id',
    ];

    public function marker()
    {
        return $this->belongsTo(Marker::class, 'marker_id', 'id');
    }

    public function media()
    {
        return $this->belongsTo(Media::class, 'media_id', 'id');
    }
}
