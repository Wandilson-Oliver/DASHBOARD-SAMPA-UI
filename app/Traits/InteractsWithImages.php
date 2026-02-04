<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

trait InteractsWithImages
{
    protected function storeOptimizedImage(
        UploadedFile $file,
        string $directory,
        int $maxWidth = 1920,
        int $quality = 80
    ): string {
        $manager = new ImageManager(new Driver());

        $image = $manager->read($file->getPathname());

        // Redimensiona mantendo proporção
        $image->scaleDown(width: $maxWidth);

        $filename = uniqid() . '.jpg';
        $path = "{$directory}/{$filename}";

        Storage::disk('public')->put(
            $path,
            $image->toJpeg($quality)
        );

        return $path;
    }
}
