<?php

namespace App\Transformers;

use App\Models\Plate;
use League\Fractal\TransformerAbstract;

class PlateTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     */
    protected array $defaultIncludes = [
        //
    ];

    /**
     * List of resources possible to include
     */
    protected array $availableIncludes = [
        'markers',
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Plate $plate)
    {
        return [
            'id' => $plate->id,
            'number' => $plate->number,
            'link' => $plate->link(),
        ];
    }

    public function includeMarkers(Plate $plate)
    {
        return $this->collection($plate->markers, new MarkerTransformer);
    }
}
