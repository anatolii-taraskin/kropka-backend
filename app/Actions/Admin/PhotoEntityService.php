<?php

namespace App\Actions\Admin;

use App\Actions\Admin\Concerns\HandlesPhotoUploads;
use App\Services\SortOrderService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

class PhotoEntityService
{
    use HandlesPhotoUploads;

    public function __construct(private readonly SortOrderService $sortOrderService)
    {
    }

    /**
     * @template T of Model
     *
     * @param  class-string<T>  $modelClass
     * @param  array<string, mixed>  $data
     * @return T
     */
    public function create(string $modelClass, array $data, ?UploadedFile $photo, string $disk, string $directory): Model
    {
        $data['sort'] = $data['sort'] ?? $this->sortOrderService->nextSortValue($modelClass);

        if ($photo) {
            $data['photo_path'] = $this->storePhoto($photo, $directory, $disk);
        }

        /** @var T $model */
        $model = $modelClass::query()->create($data);

        return $model;
    }

    /**
     * @template T of Model
     *
     * @param  T  $model
     * @param  array<string, mixed>  $data
     * @return T
     */
    public function update(Model $model, array $data, ?UploadedFile $photo, string $disk, string $directory): Model
    {
        if ($photo) {
            $newPhotoPath = $this->storePhoto($photo, $directory, $disk);
            $this->deletePhoto($model->getAttribute('photo_path'), $disk);
            $data['photo_path'] = $newPhotoPath;
        }

        $model->update($data);

        return $model;
    }

    public function delete(Model $model, string $disk): void
    {
        $this->deletePhoto($model->getAttribute('photo_path'), $disk);

        $model->delete();
    }
}
