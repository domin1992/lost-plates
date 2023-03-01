<?php

namespace App\Transformers;

use App\Models\MarkerMedia;
use League\Fractal\TransformerAbstract;

class MarkerMediaTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected array $defaultIncludes = [
        'media',
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
