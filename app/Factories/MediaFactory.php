<?php

namespace App\Factories;

use App\Models\Media;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaFactory
{
    public function addFile(
        UploadedFile|string $file,
        string $type,
        ?string $imageType = null,
        ?string $userId = null
    ): Media {
        if (is_a($file, UploadedFile::class)) {
            $extension = $file->extension();
        } else {
            $extension = $this->getExtension($file);
        }

        $media = Media::createObject($extension, $type, $imageType, $userId);
        $media->addFile($file);

        return $media;
    }

    public function addFromUrl(
        string $url,
        string $type,
        ?string $imageType = null,
        ?string $userId = null
    ): Media {
        $storage = Storage::disk('local');
        $storage->makeDirectory('tmp');

        $randomFileName = Str::random(12);
        $extension = $this->getExtension($url);
        $filePath = storage_path('app/tmp/' . $randomFileName . '.' . $extension);

        Http::sink($filePath)->get($url);

        $media = Media::createObject($extension, $type, $imageType, $userId);
        $media->addFile($filePath);

        $storage->delete($filePath);

        return $media;
    }

    private function getExtension(string $file): string
    {
        $fileSize = getimagesize($file);
        return str_replace('.', '', image_type_to_extension($fileSize[2]));
    }
}