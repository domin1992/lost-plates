<?php

namespace App\Libraries;

use Illuminate\Support\Facades\Http;

class GoogleGeocoding
{
    const STATUS_OK = 'OK';

    public function __construct(private string $googleCloudApiKey)
    {
        //
    }

    public function geocodeByPlaceId(string $placeId)
    {
        return Http::get('https://maps.googleapis.com/maps/api/geocode/json', [
            'place_id' => $placeId,
            'key' => $this->googleCloudApiKey,
        ])->json();
    }

    public function geocodeByAddress(string $address)
    {
        return Http::get('https://maps.googleapis.com/maps/api/geocode/json', [
            'address' => $address,
            'key' => $this->googleCloudApiKey,
        ])->json();
    }

    public function geocodeByLatLng(float $lat, float $lng)
    {
        return Http::get('https://maps.googleapis.com/maps/api/geocode/json', [
            'latlng' => $lat . ',' . $lng,
            'key' => $this->googleCloudApiKey,
        ])->json();
    }
}
