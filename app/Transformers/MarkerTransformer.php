<?php

namespace App\Transformers;

use App\Models\Marker;
use App\Models\MarkerComment;
use League\Fractal\TransformerAbstract;

class MarkerTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     */
    protected array $defaultIncludes = [
        'markerMedia',
        'markerComments',
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
            'formattedAddress' => $marker->formatted_address,
            'googleMapsLink' => $marker->googleMapsLink(),
            'googlePlaceId' => $marker->google_place_id,
            'radius' => $marker->radius,
            'additionalInfo' => $marker->additional_info,
            'plateNumber' => $plate->number,
            'phoneNumber' => $marker->hiddenPhoneNumber(),
            'hasPhoneNumber' => !is_null($marker->phone_number) && !empty($marker->phone_number),
            'isPhoneNumberRevealed' => false,
            'hasEmail' => !is_null($marker->email) && !empty($marker->email),
            'createdAtForHumans' => $marker->created_at->diffForHumans(),
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
