<?php

namespace App\Models;

use App\Models\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Session;

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
        'plate',
    ];

    protected $casts = [
        'lat' => 'float',
        'lng' => 'float',
    ];

    public function plate(): Relation
    {
        return $this->belongsTo(Plate::class, 'plate_id', 'id');
    }

    public function markerMedia(): Relation
    {
        return $this->hasMany(MarkerMedia::class);
    }

    public function markerComments(): Relation
    {
        return $this->hasMany(MarkerComment::class);
    }

    public function hiddenPhoneNumber(): ?string
    {
        if (null == $this->phone_number) {
            return null;
        }

        $phoneNumber = substr($this->phone_number, 0, 3);

        return $phoneNumber . 'xxxxxx';
    }

    public function formattedPhoneNumber(): ?string
    {
        if (null == $this->phone_number) {
            return null;
        }

        return $this->phone_number;
    }

    public function googleMapsLink(): string
    {
        return 'https://www.google.com/maps/search/?api=1&query=' . $this->lat . ',' . $this->lng;
    }
}
