<?php

namespace App\Actions\Admin\Concerns;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait HandlesPhotoUploads
{
    protected function storePhoto(?UploadedFile $photo, string $directory, string $disk): ?string
    {
        if (! $photo) {
            return null;
        }

        return $photo->store($directory, $disk);
    }

    protected function deletePhoto(?string $path, string $disk): void
    {
        if (! $path) {
            return;
        }

        Storage::disk($disk)->delete($path);
    }
}
