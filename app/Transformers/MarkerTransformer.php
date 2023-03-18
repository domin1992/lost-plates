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
        'marker_comments',
        'plate',
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
            'google_maps_link' => $marker->googleMapsLink(),
            'google_place_id' => $marker->google_place_id,
            'radius' => $marker->radius,
            'additional_info' => $marker->additional_info,
            'plate_number' => $plate->number,
            'phone_number' => $marker->hiddenPhoneNumber(),
            'has_email' => $marker->email,
            'link' => $marker->link(),
            'created_at_for_humans' => $marker->created_at->diffForHumans(),
        ];
    }

    public function includeMarkerMedia(Marker $marker)
    {
        return $this->collection($marker->markerMedia, new MarkerMediaTransformer);
    }

    public function includeMarkerComments(Marker $marker)
    {
        return $this->collection($marker->markerComments, new MarkerCommentTransformer);
    }

    public function includePlate(Marker $marker)
    {
        return $this->item($marker->plate, new PlateTransformer);
    }
}
