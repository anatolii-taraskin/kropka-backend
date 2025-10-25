<?php

namespace App\Actions\Admin\Price;

use App\Http\Requests\Admin\Price\PriceRequest;
use App\Models\Price;
use App\Services\SortOrderService;

class PriceManager
{
    public function __construct(private readonly SortOrderService $sortOrderService)
    {
    }

    public function create(PriceRequest $request): Price
    {
        $data = $request->sanitized();
        $data['sort'] = $this->sortOrderService->nextSortValue(Price::class);

        return Price::create($data);
    }

    public function update(PriceRequest $request, Price $price): Price
    {
        $data = $request->sanitized();

        $price->update($data);

        return $price;
    }

    public function delete(Price $price): void
    {
        $price->delete();
    }
}

