<?php

namespace App\Models;

use App\Models\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarkerComment extends Model
{
    use HasFactory, Uuids;

    protected $fillable = [
        'marker_id',
        'name',
        'content',
    ];
}
