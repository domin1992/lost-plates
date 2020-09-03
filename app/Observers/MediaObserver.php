<?php

namespace App\Observers;

use App\Defines\ImageSize;
use App\Models\Media;
use App\Libraries\ImageProcessor;

class MediaObserver
{
    /**
     * Handle the media "created" event.
     *
     * @param  \App\Models\Media  $media
     * @return void
     */
    public function created(Media $media)
    {
        if($media->file_type == 'image'){
            $imageSize = ImageSize::getBySlug($media->image_type);
            if($imageSize != null){
                $imageProcessor = new ImageProcessor;
                $imageProcessor->resizeImage(storage_path($imageSize['directory']), $media->file_name, $media->file_extension, $imageSize['sizes']);
            }
        }
    }

    /**
     * Handle the media "updated" event.
     *
     * @param  \App\Models\Media  $media
     * @return void
     */
    public function updated(Media $media)
    {
        //
    }

    /**
     * Handle the media "deleted" event.
     *
     * @param  \App\Models\Media  $media
     * @return void
     */
    public function deleted(Media $media)
    {
        $media->filesDelete();
    }

    /**
     * Handle the media "restored" event.
     *
     * @param  \App\Models\Media  $media
     * @return void
     */
    public function restored(Media $media)
    {
        //
    }

    /**
     * Handle the media "force deleted" event.
     *
     * @param  \App\Models\Media  $media
     * @return void
     */
    public function forceDeleted(Media $media)
    {
        //
    }
}
