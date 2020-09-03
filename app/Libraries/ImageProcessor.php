<?php

namespace App\Libraries;

use App\Defines\ImageSize;
use Intervention\Image\ImageManager;
use Storage;

class ImageProcessor
{
    private $imageManager;

    function __construct()
    {
        $this->imageManager = new ImageManager;
    }

    public function store($file, $media, $imageSize)
    {
        Storage::disk('public')->putFileAs('images/'.$media->image_type, $file, $media->file_name.'.'.$media->file_extension);
        $this->resizeImage(storage_path('app/public/images/'.$media->image_type.'/'), $media->file_name, $media->file_extension, $imageSize['sizes']);
    }

    public function resize($filepath, $filename, $filetype, $newfilename, $width, $height, $cut = false)
    {
        $imageManager = $this->imageManager->make($filepath.$filename.'.'.$filetype);

        if($imageManager->width() >= $width && $imageManager->height() >= $height){
            if($cut){
                $pretendHeight = $this->calcRatio($width, $imageManager->height(), $imageManager->width());
                if($pretendHeight >= $height){
                    $imageManager->resize($width, $pretendHeight)->crop($width, $height);
                }
                else{
                    $pretendWidth = $this->calcRatio($height, $imageManager->width(), $imageManager->height());
                    if($pretendWidth >= $width){
                        $imageManager->resize($pretendWidth, $height)->crop($width, $height);
                    }
                }
            }
            else{
                if(($imageManager->width() - $width) < ($imageManager->height() - $height)){
                    $imageManager->resize($this->calcRatio($height, $imageManager->width(), $imageManager->height()), $height)->resizeCanvas($width, $height, 'center', false);
                }
                elseif($imageManager->width() - $width == $imageManager->height() - $height){
                    $imageManager->resize($width, $height);
                }
                else{
                    $imageManager->resize($width, $this->calcRatio($width, $imageManager->height(), $imageManager->width()))->resizeCanvas($width, $height, 'center', false);
                }
            }
        }
        elseif($imageManager->width() < $width && $imageManager->height() < $height){
            $imageManager->resizeCanvas($width, $height, 'center', false);
        }
        elseif($imageManager->width() >= $width && $imageManager->height() < $height){
            if($cut){
                $imageManager->crop($width, $imageManager->height())->resizeCanvas($width, $height, 'center', false);
            }
            else{
                $imageManager->resize($width, $this->calcRatio($width, $imageManager->height(), $imageManager->width()))->resizeCanvas($width, $height, 'center', false);
            }
        }
        elseif($imageManager->width() < $width && $imageManager->height() >= $height){
            if($cut){
                $imageManager->crop($imageManager->width(), $height)->resizeCanvas($width, $height, 'center', false);
            }
            else{
                $imageManager->resize($this->calcRatio($height, $imageManager->width(), $imageManager->height()), $height)->resizeCanvas($width, $height, 'center', false);
            }
        }

        $imageManager->save($filepath.$newfilename.'.'.$filetype);
    }

    public function resizeImage($filepath, $filename, $filetype, $sizes, $cut = false)
    {
        if($filetype != 'gif' && $filetype != 'GIF'){
            foreach($sizes as $key => $value){
                $this->resize($filepath, $filename, $filetype, $filename.'_'.$key, $value['width'], $value['height'], (isset($value['cut']) ? $value['cut'] : $cut));
            }
        }
    }

    public function calcRatio($a, $b, $c)
    {
        return (int)(($a * $b) / $c);
    }

    public function regenerateImages($filepath, $filename, $filetype, $sizes, $cut = false)
    {
        foreach($sizes as $key => $value){
            $imageManager = $this->imageManager->make($filepath.$filename.'.'.$filetype);
            if(!Storage::disk('public')->exists($filepath.$filename.'_'.$key.'.'.$filetype)){
                $this->resize($imageManager, $filepath, $filename, $filetype, $filename.'_'.$key, $value['width'], $value['height'], $cut);
            }
        }
    }

    public function crop($filepath, $filename, $filetype, $points, $sizes)
    {
        $imageManager = $this->imageManager->make($filepath.$filename.'.'.$filetype);
        $imageManager->crop($points[2] - $points[0], $points[3] - $points[1], $points[0], $points[1]);
        $imageManager->save($filepath.$filename.'.'.$filetype);
        $this->regenerateImages($filepath, $filename, $filetype, $sizes);
    }
}
