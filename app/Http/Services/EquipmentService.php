<?php

namespace App\Http\Services;

use App\Actions\Admin\PhotoEntityService;
use App\Actions\Admin\ReorderRecords;
use App\Config\Media\EquipmentPhotoEntityConfig;
use App\Http\Requests\Admin\Equipment\EquipmentRequest;
use App\Models\Equipment;

class EquipmentService
{
    public function __construct(
        private readonly PhotoEntityService $photoEntityService,
        private readonly EquipmentPhotoEntityConfig $config,
        private readonly ReorderRecords $reorderRecords,
    ) {
    }

    public function create(EquipmentRequest $request): Equipment
    {
        $data = $request->sanitized();

        /** @var Equipment $equipment */
        $equipment = $this->photoEntityService->create(
            Equipment::class,
            $data,
            $request->file('photo'),
            $this->config,
        );

        return $equipment;
    }

    public function update(EquipmentRequest $request, Equipment $equipment): Equipment
    {
        $data = $request->sanitized();

        /** @var Equipment $equipment */
        $equipment = $this->photoEntityService->update(
            $equipment,
            $data,
            $request->file('photo'),
            $this->config,
        );

        return $equipment;
    }

    public function delete(Equipment $equipment): void
    {
        $this->photoEntityService->delete($equipment, $this->config);
    }

    public function reorder(array $ids): void
    {
        $this->reorderRecords->handle(Equipment::class, $ids);
    }
}
