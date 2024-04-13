<?php

namespace App\Processors;

use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\ImageManager;
use Intervention\Image\Interfaces\ImageInterface;

class ImageProcessor
{
    private ImageManager $imageManager;
    private ImageInterface $image;

    public function __construct()
    {
        $this->imageManager = new ImageManager(Driver::class);
    }

    public function setImage(mixed $image): self
    {
        $this->image = $this->imageManager->read($image);

        return $this;
    }

    public function resize(?int $width = null, ?int $height = null, string $fit = 'cover'): self
    {
        if ((is_null($width) || $width == 'auto') && (is_null($height) || $height == 'auto')) {
            throw new \Exception('Width or height is required');
        }

        if (!in_array($fit, ['cover', 'contain'])) {
            throw new \Exception('Invalid fit type: ' . $fit);
        }

        if (is_null($width) || $width == 'auto') {
            $this->image->resize(height: $height);

            return $this;
        }

        if (is_null($height) || $height == 'auto') {
            $this->image->resize(width: $width);

            return $this;
        }

        if ($fit === 'cover') {
            $this->image->cover($width, $height);
        } elseif ($fit === 'contain') {
            $this->image->contain($width, $height);
        }

        return $this;
    }

    public function crop(int $width, int $height, int $x, int $y): self
    {
        $this->image->crop($width, $height, $x, $y);

        return $this;
    }

    public function save(string $path): void
    {
        $this->image->save($path);
    }
}