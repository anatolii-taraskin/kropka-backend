<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Api\Public\Concerns\ResolvesLocale;
use App\Http\Controllers\Controller;
use App\Services\Api\Public\PriceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PriceController extends Controller
{
    use ResolvesLocale;

    public function __construct(private readonly PriceService $priceService)
    {
    }

    /**
     * Return the list of published prices in the requested locale.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $locale = $this->localeFrom($request);

        return response()->json([
            'data' => $this->priceService->list($locale),
            'meta' => [
                'locale' => $locale,
            ],
        ]);
    }
}
