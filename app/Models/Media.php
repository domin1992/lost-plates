<?php

namespace App\Models;

use App\Jobs\MediaResizeJob;
use App\Models\Traits\Uuids;
use App\Processors\ImageProcessor;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Media extends Model
{
    use HasFactory, Uuids, Prunable;

    protected $fillable = [
        'user_id',
        'type',
        'image_type',
        'name',
        'extension',
    ];

    const TYPE_IMAGE = 'image';

    public static function createObject(
        string $extension,
        string $type,
        ?string $imageType = null,
        ?string $userId = null
    ): self {
        $name = self::generateName();

        return self::create([
            'name' => $name,
            'extension' => $extension,
            'type' => $type,
            'image_type' => $imageType,
            'user_id' => $userId,
        ]);
    }

    public function addFile(UploadedFile|string $file, bool $asyncResizing = false): void
    {
        $this->determinePathExists();

        $storage = $this->getStorage();

        if ($this->type === self::TYPE_IMAGE) {
            $this->assertImageTypeValid();

            $storage->putFileAs(
                sprintf('%s/%s', $this->type, $this->image_type),
                $file,
                sprintf('%s.%s', $this->name, $this->extension)
            );

            if ($asyncResizing) {
                MediaResizeJob::dispatch($this->id);
            } else {
                $this->resizeImage();
            }
        } else {
            $storage->putFileAs(
                sprintf('%s', $this->type),
                $file,
                sprintf('%s.%s', $this->name, $this->extension)
            );
        }
    }

    public function fileExists(?string $imageSize = null): bool
    {
        return $this->getStorage()
            ->exists(
                $this->getFilePath($imageSize)
            );
    }

    public function getFilePath(?string $imageSize = null): string
    {
        if ($this->type == self::TYPE_IMAGE) {
            $name = $this->name;

            if ($imageSize) {
                $name .= '_' . $imageSize;
            }

            return sprintf(
                '%s/%s/%s.%s',
                $this->type,
                $this->image_type,
                $name,
                $this->extension
            );
        }

        return sprintf(
            '%s/%s.%s',
            $this->type,
            $this->name,
            $this->extension
        );
    }

    public function deleteAllFiles(): bool
    {
        if ($this->type == self::TYPE_IMAGE) {
            foreach (array_keys(config('media-manager.image-types')[$this->image_type]) as $imageSizeSlug) {
                $this->getStorage()->delete(
                    $this->getFilePath($imageSizeSlug)
                );
            }
        }

        return $this->getStorage()->delete(
            $this->getFilePath()
        );
    }

    public function url(?string $imageSize = null): string
    {
        $storage = $this->getStorage();
        return $storage->url(
            $imageSize && $this->fileExists($imageSize)
                ? $this->getFilePath($imageSize)
                : $this->getFilePath()
        );
    }

    public function content(?string $imageSize = null): string
    {
        $storage = $this->getStorage();

        return mb_convert_encoding(
            file_get_contents(
                $imageSize && $this->fileExists($imageSize)
                    ? $storage->path($this->getFilePath($imageSize))
                    : $storage->path($this->getFilePath())
            ),
            'UTF-8'
        );
    }

    public function resizeImage()
    {
        if ($this->type !== self::TYPE_IMAGE) {
            throw new \Exception('Media type is not image');
        }

        $this->assertImageTypeValid();

        $this->determinePathExists();

        $storage = $this->getStorage();

        foreach (config('media-manager.image-types.' . $this->image_type) as $imageSizeSlug => $imageSize) {
            $this->resizeImageByImageSize(
                $storage->path($this->getFilePath()),
                $storage->path($this->getFilePath($imageSizeSlug)),
                $imageSize['width'],
                $imageSize['height'],
                $imageSize['fit']
            );
        }
    }

    private function resizeImageByImageSize(string $sourceFilePath, string $destinationFilePath, int $width, int $height, string $fit)
    {
        $imageProcessor = app(ImageProcessor::class);

        $imageProcessor
            ->setImage($sourceFilePath)
            ->resize(
                $width,
                $height,
                $fit
            )
            ->save($destinationFilePath);
    }

    public function resizeMissingImages()
    {
        if ($this->type !== self::TYPE_IMAGE) {
            throw new \Exception('Media type is not image');
        }

        $storage = $this->getStorage();

        foreach (config('media-manager.image-types.' . $this->image_type) as $imageSizeSlug => $imageSize) {
            if (!$this->fileExists($imageSizeSlug)) {
                $this->resizeImageByImageSize(
                    $storage->path($this->getFilePath()),
                    $storage->path($this->getFilePath($imageSizeSlug)),
                    $imageSize['width'],
                    $imageSize['height'],
                    $imageSize['fit']
                );
            }
        }
    }

    public static function staticResizeAllMediaImages(?string $imageType = null)
    {
        Media::where('type', self::TYPE_IMAGE)
            ->where(function ($query) use ($imageType) {
                if ($imageType) {
                    $query->where('image_type', $imageType);
                }
            })
            ->get()
            ->each(function (Media $media) {
                MediaResizeJob::dispatch($media->id);
            });
    }

    protected static function generateName()
    {
        do {
            $name = Str::random(32);
        } while (self::where('name', $name)->first());

        return $name;
    }

    public function crop(int $width, int $height, int $x, int $y): void
    {
        $storage = $this->getStorage();

        if ($this->type == self::TYPE_IMAGE) {
            $imageProcessor = app(ImageProcessor::class);
            $imageProcessor
                ->setImage($storage->path($this->getFilePath()))
                ->crop($width, $height, $x, $y)
                ->save($storage->path($this->getFilePath()));

            $this->resizeImage();
        }
    }

    public function copyMedia(): Media
    {
        $newMedia = self::createObject(
            $this->extension,
            $this->type,
            $this->image_type,
            $this->user_id
        );

        $newMedia->addFile($this->getStorage()->path($this->getFilePath()));

        return $newMedia;
    }

    private function getStorage(): FilesystemAdapter
    {
        return Storage::disk(config('media-manager.disk'));
    }

    private function determinePathExists(): void
    {
        $storage = $this->getStorage();
        $path = $this->type . ($this->type === self::TYPE_IMAGE ? '/' . $this->image_type : '');

        if (!$storage->exists($path)) {
            $storage->makeDirectory($path);
        }
    }

    private function assertImageTypeValid(): bool
    {
        if (is_null($this->image_type) || !$this->image_type) {
            throw new \Exception('Image type is required');
        }

        if (!isset(config('media-manager.image-types')[$this->image_type])) {
            throw new \Exception('Image type is not defined in config');
        }

        return true;
    }

    /**
     * Get the prunable model query.
     */
    public function prunable(): Builder
    {
        return static::where('created_at', '<=', now()->subDay())
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('marker_media')
                    ->whereRaw('marker_media.media_id = media.id');
            });
    }
}
