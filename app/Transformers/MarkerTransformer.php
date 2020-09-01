<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Marker;

class MarkerTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        //
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
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
}
