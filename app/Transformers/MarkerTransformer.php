<?php

namespace App\Transformers;

use App\Models\Marker;
use League\Fractal\TransformerAbstract;

class MarkerTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     */
    protected array $defaultIncludes = [
        'marker_media',
    ];

    /**
     * List of resources possible to include
     */
    protected array $availableIncludes = [
        //
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Marker $marker)
    {
        $plate = $marker->plate()->first();

        return [
            'id' => $marker->id,
            'type' => $marker->type,
            'lat' => $marker->lat,
            'lng' => $marker->lng,
            'formatted_address' => $marker->formatted_address,
            'google_place_id' => $marker->google_place_id,
            'radius' => $marker->radius,
            'additional_info' => $marker->additional_info,
            'plate_number' => $plate->number,
            'phone_number' => $marker->hiddenPhoneNumber(),
            'link' => $marker->link(),
        ];
    }

    public function includeMarkerMedia(Marker $marker)
    {
        return $this->collection($marker->markerMedia, new MarkerMediaTransformer);
    }
}
