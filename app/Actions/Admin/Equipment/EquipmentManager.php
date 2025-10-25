<?php

namespace App\Actions\Admin\Equipment;

use App\Actions\Admin\PhotoEntityService;
use App\Config\Media\EquipmentPhotoEntityConfig;
use App\Http\Requests\Admin\Equipment\EquipmentRequest;
use App\Models\Equipment;

class EquipmentManager
{
    public function __construct(
        private readonly PhotoEntityService $photoEntityService,
        private readonly EquipmentPhotoEntityConfig $config
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
            $this->config
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
            $this->config
        );

        return $equipment;
    }

    public function delete(Equipment $equipment): void
    {
        $this->photoEntityService->delete($equipment, $this->config);
    }
}
