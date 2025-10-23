<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Api\Public\Concerns\ResolvesLocale;
use App\Http\Controllers\Controller;
use App\Models\Price;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PriceController extends Controller
{
    use ResolvesLocale;

    /**
     * Return the list of published prices in the requested locale.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $locale = $this->localeFrom($request);

        $prices = Price::query()
            ->where('is_active', true)
            ->orderBy('sort')
            ->orderBy('id')
            ->get()
            ->map(function (Price $price) use ($locale) {
                return [
                    'id' => $price->id,
                    'name' => $price->localizedName($locale),
                    'columns' => collect([1, 2, 3])
                        ->map(fn (int $index) => [
                            'position' => $index,
                            'value' => $price->localizedColumn($index, $locale),
                        ])
                        ->filter(fn (array $column) => filled($column['value']))
                        ->values()
                        ->all(),
                    'sort' => $price->sort,
                ];
            });

        return response()->json([
            'data' => $prices,
            'meta' => [
                'locale' => $locale,
            ],
        ]);
    }
}
