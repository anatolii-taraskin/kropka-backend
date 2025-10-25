<?php

namespace App\Services\Api\Public;

use App\Models\Price;

class PriceService
{
    public function __construct(private readonly Price $price)
    {
    }

    public function list(string $locale): array
    {
        return $this->price->newQuery()
            ->where('is_active', true)
            ->orderBy('sort')
            ->orderBy('id')
            ->get()
            ->map(function (Price $price) use ($locale) {
                $columns = collect([1, 2, 3])
                    ->map(fn (int $index) => [
                        'position' => $index,
                        'value' => $price->localizedColumn($index, $locale),
                    ])
                    ->filter(fn (array $column) => filled($column['value']))
                    ->values()
                    ->all();

                return [
                    'id' => $price->id,
                    'name' => $price->localizedName($locale),
                    'columns' => $columns,
                    'sort' => $price->sort,
                ];
            })
            ->values()
            ->all();
    }
}
