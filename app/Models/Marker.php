<?php

namespace App\Models;

use App\Models\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marker extends Model
{
    use HasFactory, Uuids;

    const TYPE_FOUND = 'found';
    const TYPE_LOST = 'lost';

    protected $fillable = [
        'plate_id',
        'type',
        'lat',
        'lng',
        'formatted_address',
        'google_place_id',
        'radius',
        'phone_number_prefix_id',
        'phone_number',
        'email',
        'additional_info',
        'notify_when_found',
    ];

    protected $with = [
        'markerMedia',
        'markerComments',
    ];

    public function plate()
    {
        return $this->belongsTo(Plate::class, 'plate_id', 'id');
    }

    public function markerMedia()
    {
        return $this->hasMany(MarkerMedia::class);
    }

    public function markerComments()
    {
        return $this->hasMany(MarkerComment::class);
    }

    public function hiddenPhoneNumber()
    {
        if (null == $this->phone_number) {
            return null;
        }
        $phoneNumber = substr($this->phone_number, 0, 3);

        return $phoneNumber . 'xxxxxx';
    }

    public function formattedPhoneNumber()
    {
        if (null == $this->phone_number) {
            return null;
        }

        return $this->phone_number;
    }

    public function link()
    {
        return route('front.markers.show', [
            'type' => $this->type,
            'plateNumber' => $this->plate->number,
        ]);
    }
}
