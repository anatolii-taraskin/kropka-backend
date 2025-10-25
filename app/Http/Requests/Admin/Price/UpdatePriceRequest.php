<?php

namespace App\Http\Requests\Admin\Price;

use App\Models\Price;

class UpdatePriceRequest extends PriceRequest
{
    protected $errorBag = 'updatePrice';

    protected function prepareForValidation(): void
    {
        /** @var Price|null $price */
        $price = $this->route('price');

        if ($price) {
            $this->errorBag = 'updatePrice' . $price->getKey();
        }
    }
}

