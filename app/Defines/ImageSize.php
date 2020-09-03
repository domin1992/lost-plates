<?php

namespace App\Defines;

use App\Defines\Enum;

class ImageSize extends Enum{
    const MARKER = [
        'slug' => 'marker',
        'directory' => '/app/public/images/marker/',
        'sizes' => [
            'miniature' => [
                'width' => 600,
                'height' => 600,
                'cut' => true,
            ],
            'hd' => [
                'width' => 1920,
                'height' => 1080,
            ],
        ],
    ];

    public static function getBySlug($slug){
        foreach(self::getConstants() as $constant){
            if($constant['slug'] == $slug){
                return $constant;
            }
        }
        return null;
    }
}
