<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Libraries\ImageProcessor;
use App\Libraries\Tools;
use App\Defines\ImageSize;
use Storage;
use File;

class Media extends Model
{
    public static function addFromUpload($file, $fileType, $imageType = null, $userId = null)
    {
        Tools::initDirectories();

        do{
            $filename = Tools::generateRandomString(32, 'nl');
        }while(Media::where('file_name', $filename)->first() != null);

        $media = new Media;
        $media->user_id = $userId;
        $media->file_type = $fileType;
        $media->image_type = $imageType;
        $media->file_name = $filename;
        $media->file_extension = $file->extension();

        if($media->file_type == 'image'){
            $imageSize = ImageSize::getBySlug($imageType);
            $imageProcessor = new ImageProcessor;
            $imageProcessor->store($file, $media, $imageSize);
        }
        elseif($media->file_type == 'document'){
            Storage::disk('public')->putFileAs('documents', $file, $media->file_name.'.'.$media->file_extension);
        }
        elseif($media->file_type == 'video'){
            Storage::disk('public')->putFileAs('videos', $file, $media->file_name.'.'.$media->file_extension);
        }

        $media->save();

        return $media;
    }

    public static function addFile($filepath, $fileType, $imageType = null, $userId = null)
    {
        Tools::initDirectories();

        do{
            $filename = Tools::generateRandomString(32, 'nl');
        }while(Media::where('file_name', $filename)->first() != null);

        $media = new Media;
        $media->user_id = $userId;
        $media->file_type = $fileType;
        $media->image_type = $imageType;
        $media->file_name = $filename;
        $media->file_extension = File::extension($filepath);

        if($media->file_type == 'image'){
            $imageSize = ImageSize::getBySlug($imageType);
            File::copy($filepath, storage_path('app/public/images/'.$media->image_type.'/'.$media->file_name.'.'.$media->file_extension));
            $imageProcessor = new ImageProcessor;
            // $imageProcessor->store($file, $media, $imageSize);
            if($imageSize != null){
                $imageProcessor->resizeImage(storage_path('app/public/images/'.$media->image_type.'/'), $media->file_name, $media->file_extension, $imageSize['sizes']);
            }
        }
        elseif($media->file_type == 'document'){
            File::copy($filepath, storage_path('app/public/documents/'.$media->file_name.'.'.$media->file_extension));
        }
        elseif($media->file_type == 'video'){
            File::copy($filepath, storage_path('app/public/videos/'.$media->file_name.'.'.$media->file_extension));
        }

        $media->save();

        return $media;
    }

    public static function addFromUrl($fileUrl, $fileType, $imageType = null, $userId = null)
    {
        Tools::initDirectories();

        $fileSize = getimagesize($fileUrl);
        $fileExtension = str_replace('.', '', image_type_to_extension($fileSize[2]));

        do{
            $filename = Tools::generateRandomString(32, 'nl');
        }while(Media::where('file_name', $filename)->first() != null);

        $media = new Media;
        $media->user_id = $userId;
        $media->file_type = $fileType;
        $media->image_type = $imageType;
        $media->file_name = $filename;
        $media->file_extension = $fileExtension;

        if($media->file_type == 'image'){
            $imageSize = ImageSize::getBySlug($imageType);
            file_put_contents(storage_path('app/public/images/'.$media->image_type.'/'.$media->file_name.'.'.$media->file_extension), file_get_contents($fileUrl));
            $imageProcessor = new ImageProcessor;
            if($imageSize != null){
                $imageProcessor->resizeImage(storage_path('app/public/images/'.$media->image_type.'/'), $media->file_name, $media->file_extension, $imageSize['sizes']);
            }
        }
        elseif($media->file_type == 'document'){
            file_put_contents(storage_path('app/public/documents/'.$media->file_name.'.'.$media->file_extension), file_get_contents($fileUrl));
        }
        elseif($media->file_type == 'video'){
            file_put_contents(storage_path('app/public/videos/'.$media->file_name.'.'.$media->file_extension), file_get_contents($fileUrl));
        }

        $media->save();

        return $media;
    }

    public function url($size = '')
    {
        if($this->file_type == 'image'){
            if(Storage::disk('public')->exists('images/'.$this->image_type.'/'.$this->file_name.($size != '' ? '_'.$size : '').'.'.$this->file_extension) && $this->file_extension != 'gif'){
                return Storage::disk('public')->url('images/'.$this->image_type.'/'.$this->file_name.($size != '' ? '_'.$size : '').'.'.$this->file_extension);
            }
            else{
                return Storage::disk('public')->url('images/'.$this->image_type.'/'.$this->file_name.'.'.$this->file_extension);
            }
        }
        elseif($this->file_type == 'document'){
            if(Storage::disk('public')->exists('documents/'.$this->file_name.'.'.$this->file_extension)){
                return Storage::disk('public')->url('documents/'.$this->file_name.'.'.$this->file_extension);
            }
        }
        elseif($this->file_type == 'video'){
            if(Storage::disk('public')->exists('videos/'.$this->file_name.'.'.$this->file_extension)){
                return Storage::disk('public')->url('videos/'.$this->file_name.'.'.$this->file_extension);
            }
        }
    }

    public function content()
    {
        if($this->file_type == 'image'){
            return utf8_encode(file_get_contents(storage_path('app/public/images/'.$this->image_type.'/'.$this->file_name.'.'.$this->file_extension)));
        }
    }

    public function fileExists($size = '')
    {
        if($this->file_type == 'image'){
            return Storage::disk('public')->exists('images/'.$this->image_type.'/'.$this->file_name.($size != '' ? '_'.$size : '').'.'.$this->file_extension);
        }
        elseif($this->file_type == 'video'){
            return Storage::disk('public')->exists('videos/'.$this->file_name.($size != '' ? '_'.$size : '').'.'.$this->file_extension);
        }
    }

    public function fileDelete($size = '')
    {
        if($this->fileExists($size)){
            if($this->file_type == 'image'){
                return Storage::disk('public')->delete('images/'.$this->image_type.'/'.$this->file_name.($size != '' ? '_'.$size : '').'.'.$this->file_extension);
            }
            elseif($this->file_type == 'document'){
                return Storage::disk('public')->delete('documents/'.$this->file_name.($size != '' ? '_'.$size : '').'.'.$this->file_extension);
            }
            elseif($this->file_type == 'video'){
                return Storage::disk('public')->delete('videos/'.$this->file_name.($size != '' ? '_'.$size : '').'.'.$this->file_extension);
            }
        }
    }

    public function filesDelete()
    {
        if($this->file_type == 'image'){
            $imageSize = ImageSize::getBySlug($this->image_type);
            foreach($imageSize['sizes'] as $sizeName => $size){
                $this->fileDelete($sizeName);
            }
            $this->fileDelete();
        }
        elseif($this->file_type == 'document'){
            $this->fileDelete();
        }
        elseif($this->file_type == 'video'){
            $this->fileDelete();
        }
    }

    public static function resizeMissingImages($imageType)
    {
        $medias = Media::where('image_type', $imageType)->get();
        $imageSize = ImageSize::getBySlug($imageType);
        $imageProcessor = new ImageProcessor;

        foreach($medias as $media){
            if($media->fileExists()){
                foreach($imageSize['sizes'] as $slug => $size){
                    if(!$media->fileExists($slug)){
                        $imageProcessor->resize(storage_path('app/public/images/'.$media->image_type.'/'), $media->file_name, $media->file_extension, $media->file_name.'_'.$slug, $size['width'], $size['height'], false);
                    }
                }
            }
        }
    }

    public static function resizeImages($imageType)
    {
        $medias = Media::where('image_type', $imageType)->get();
        $imageSize = ImageSize::getBySlug($imageType);
        $imageProcessor = new ImageProcessor;

        foreach($medias as $media){
            if($media->fileExists()){
                foreach($imageSize['sizes'] as $slug => $size){
                    $imageProcessor->resize(storage_path('app/public/images/'.$media->image_type.'/'), $media->file_name, $media->file_extension, $media->file_name.'_'.$slug, $size['width'], $size['height'], false);
                }
            }
        }
    }
}
