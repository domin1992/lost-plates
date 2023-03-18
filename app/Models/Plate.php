<?php

namespace App\Models;

use App\Models\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plate extends Model
{
    use HasFactory, Uuids;

    protected $fillable = [
        'number',
    ];

    public function markers()
    {
        return $this->hasMany(Marker::class);
    }

    public function link(): string
    {
        return route('front.plates.show', [
            'lang' => app()->getLocale(),
            'number' => $this->number,
        ]);
    }
}
