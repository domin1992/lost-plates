<?php

namespace App\Services;

use App\Libraries\Tools;
use App\Models\Marker;
use App\Models\MarkerMedia;
use App\Models\Plate;

class MarkerService
{
    public function store(
        string $plateNumber,
        string $type,
        float $lat,
        float $lng,
        array $media,
        ?string $phoneNumber,
        ?string $email,
        ?float $radius,
        ?string $additionalInfo,
        ?bool $notifyWhenFound,
    ): Marker {
        $plateNumber = Tools::formatPlateNumber($plateNumber);

        if (null == ($plate = Plate::where('number', $plateNumber)->first())) {
            $plate = Plate::create([
                'number' => $plateNumber,
            ]);
        }

        $marker = Marker::create([
            'plate_id' => $plate->id,
            'type' => $type,
            'lat' => $lat,
            'lng' => $lng,
            'radius' => $radius,
            'phone_number' => $phoneNumber
                ? Tools::simplifyPhoneNumber($phoneNumber)
                : null,
            'email' => $email,
            'additional_info' => $additionalInfo,
            'notify_when_found' => $notifyWhenFound,
        ]);

        collect($media)->map(function ($mediaId) use ($marker) {
            MarkerMedia::create([
                'marker_id' => $marker->id,
                'media_id' => $mediaId,
            ]);
        });

        return $marker;
    }
}
