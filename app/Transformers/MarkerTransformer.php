<?php

namespace App\Transformers;

use App\Models\Marker;
use League\Fractal\TransformerAbstract;

class MarkerTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected array $defaultIncludes = [
        'marker_media',
    ];
    
    /**
     * List of resources possible to include
     *
     * @var array
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
            'radius' => $marker->radius,
            'additional_info' => $marker->additional_info,
            'plate_number' => $plate->number,
            'phone_number' => $marker->hiddenPhoneNumber(),
            'email' => $marker->hiddenEmail(),
        ];
    }

    public function includeMarkerMedia(Marker $marker)
    {
        return $this->collection($marker->markerMedia, new MarkerMediaTransformer);
    }
}
