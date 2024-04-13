<?php

return [
    'disk' => 'public',

    'image-driver' => \Intervention\Image\Drivers\Imagick\Driver::class,

    'background-color' => '#FFFFFF',

    'image-types' => [
        'marker' => [
            'miniature' => [
                'width' => 80,
                'height' => 80,
                'fit' => 'cover',
            ],
            'full_hd' => [
                'width' => 1920,
                'height' => 1080,
                'fit' => 'cover',
            ],
        ],
    ],
];
