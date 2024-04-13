<?php

namespace App\Transformers;

use App\Models\Media;
use League\Fractal\TransformerAbstract;

class MediaTransformer extends TransformerAbstract
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
    public function transform(Media $media)
    {
        $url = '';

        if ($media->type == 'image') {
            $url = [];
            $url['original'] = $media->url('');
            foreach (config('media-manager.image-types') as $imageTypeSlug => $imageType) {
                if ($media->image_type == $imageTypeSlug) {
                    foreach ($imageType as $imageSizeSlug => $imageSize) {
                        $url[$imageSizeSlug] = $media->url($imageSizeSlug);
                    }
                }
            }
        } else {
            $url = $media->url();
        }

        return [
            'id' => $media->id,
            'userId' => $media->user_id,
            'fileType' => $media->type,
            'imageType' => $media->image_type,
            'fileName' => $media->name,
            'fileExtension' => $media->extension,
            'url' => $url,
        ];
    }
}
