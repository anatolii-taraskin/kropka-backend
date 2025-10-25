<?php

namespace App\Http\Requests\Admin\Equipment;

use App\Models\Equipment;

class UpdateEquipmentRequest extends EquipmentRequest
{
    protected $errorBag = 'updateEquipment';

    protected function prepareForValidation(): void
    {
        /** @var Equipment|null $equipment */
        $equipment = $this->route('equipment');

        if ($equipment) {
            $this->errorBag = 'updateEquipment' . $equipment->getKey();
        }
    }
}

