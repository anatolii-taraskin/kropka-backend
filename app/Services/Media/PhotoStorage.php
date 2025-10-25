<?php

namespace App\Services\Media;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class PhotoStorage
{
    public function store(UploadedFile $photo, string $disk, string $directory): string
    {
        return $photo->store($directory, $disk);
    }

    public function delete(?string $path, string $disk): void
    {
        if (! $path) {
            return;
        }

        Storage::disk($disk)->delete($path);
    }
}
