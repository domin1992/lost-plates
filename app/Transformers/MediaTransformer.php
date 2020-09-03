<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Defines\ImageSize;
use App\Models\Media;

class MediaTransformer extends TransformerAbstract
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
    public function transform(Media $media)
    {
        $url = '';

        if($media->file_type == 'image'){
            $imageSize = ImageSize::getBySlug($media->image_type);
            if(isset($imageSize['sizes']) && is_array($imageSize['sizes']) && count($imageSize['sizes']) > 0){
                $url = [];
                $url['original'] = $media->url('');
                foreach($imageSize['sizes'] as $key => $size){
                    $url[$key] = $media->url($key);
                }
            }
        }
        else{
            $url = $media->url();
        }

        return [
            'id' => $media->id,
            'user_id' => $media->user_id,
            'file_type' => $media->file_type,
            'image_type' => $media->image_type,
            'file_name' => $media->file_name,
            'file_extension' => $media->file_extension,
            'url' => $url,
        ];
    }
}
