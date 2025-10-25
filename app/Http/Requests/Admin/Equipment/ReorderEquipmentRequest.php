<?php

namespace App\Http\Requests\Admin\Equipment;

use App\Http\Requests\Admin\ReorderRequest;

class ReorderEquipmentRequest extends ReorderRequest
{
    protected function table(): string
    {
        return 'equipment';
    }
}

