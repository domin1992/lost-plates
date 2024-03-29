<?php

namespace App\Models;

use App\Models\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory, Uuids;

    protected $fillable = [
        'name',
        'iso',
        'date_format',
        'datetime_format',
    ];
}
