<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Zencoreitservices\MediaManager\Models\Media as Model;
use App\Models\Traits\Uuids;

class Media extends Model
{
    use HasFactory, Uuids;
}
