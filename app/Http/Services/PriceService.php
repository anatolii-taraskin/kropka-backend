<?php

namespace App\Http\Services;

use App\Actions\Admin\ReorderRecords;
use App\Http\Requests\Admin\Price\PriceRequest;
use App\Models\Price;
use App\Services\SortOrderService;

class PriceService
{
    public function __construct(
        private readonly SortOrderService $sortOrderService,
        private readonly ReorderRecords $reorderRecords,
    ) {
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

    public function reorder(array $ids): void
    {
        $this->reorderRecords->handle(Price::class, $ids);
    }
}
