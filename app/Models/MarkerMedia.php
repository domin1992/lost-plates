<?php

namespace App\Models;

use App\Models\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class MarkerMedia extends Model
{
    use HasFactory, Uuids;

    protected $fillable = [
        'marker_id',
        'media_id',
    ];

    protected $with = [
        'media',
    ];

    public function marker(): Relation
    {
        return $this->belongsTo(Marker::class, 'marker_id', 'id');
    }

    public function media(): Relation
    {
        return $this->belongsTo(Media::class, 'media_id', 'id');
    }
}
