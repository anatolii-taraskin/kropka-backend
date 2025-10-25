<?php

namespace App\Http\Requests\Admin\Price;

use App\Http\Requests\Admin\ReorderRequest;

class ReorderPriceRequest extends ReorderRequest
{
    protected function table(): string
    {
        return 'prices';
    }
}

