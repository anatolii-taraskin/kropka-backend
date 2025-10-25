<?php

namespace App\Actions\Admin;

use App\Config\Media\PhotoEntityConfig;
use App\Services\Media\PhotoStorage;
use App\Services\SortOrderService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

class PhotoEntityService
{
    public function __construct(
        private readonly SortOrderService $sortOrderService,
        private readonly PhotoStorage $photoStorage
    ) {
    }

    /**
     * @template T of Model
     *
     * @param  class-string<T>  $modelClass
     * @param  array<string, mixed>  $data
     * @return T
     */
    public function create(string $modelClass, array $data, ?UploadedFile $photo, PhotoEntityConfig $config): Model
    {
        $sortField = $config->sortField();
        if (! array_key_exists($sortField, $data) || $data[$sortField] === null) {
            $data[$sortField] = $this->sortOrderService->nextSortValue($modelClass, $sortField);
        }

        if ($photo) {
            $data['photo_path'] = $this->photoStorage->store($photo, $config->disk(), $config->directory());
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
    public function update(Model $model, array $data, ?UploadedFile $photo, PhotoEntityConfig $config): Model
    {
        if ($photo) {
            $newPhotoPath = $this->photoStorage->store($photo, $config->disk(), $config->directory());
            $this->photoStorage->delete($model->getAttribute('photo_path'), $config->disk());
            $data['photo_path'] = $newPhotoPath;
        }

        $model->update($data);

        return $model;
    }

    public function delete(Model $model, PhotoEntityConfig $config): void
    {
        $this->photoStorage->delete($model->getAttribute('photo_path'), $config->disk());

        $model->delete();
    }
}
