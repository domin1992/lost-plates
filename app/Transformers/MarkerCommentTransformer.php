<?php

namespace App\Transformers;

use App\Models\MarkerComment;
use League\Fractal\TransformerAbstract;

class MarkerCommentTransformer extends TransformerAbstract
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
        //
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(MarkerComment $markerComment)
    {
        return [
            'id' => $markerComment->id,
            'markerId' => $markerComment->marker_id,
            'name' => $markerComment->name,
            'content' => $markerComment->content,
            'createdAtDisplay' => $markerComment->created_at->diffForHumans(),
            'createdAt' => $markerComment->created_at,
        ];
    }
}
