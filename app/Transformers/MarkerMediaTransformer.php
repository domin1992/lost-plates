<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\MarkerMedia;

class MarkerMediaTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        'media',
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
    public function transform(MarkerMedia $markerMedia)
    {
        return [
            'id' => $markerMedia->id,
            'marker_id' => $markerMedia->marker_id,
            'media_id' => $markerMedia->media_id,
        ];
    }

    public function includeMedia(MarkerMedia $markerMedia)
    {
        $media = $markerMedia->media()->first();

        return $this->item($media, new MediaTransformer);
    }
}
