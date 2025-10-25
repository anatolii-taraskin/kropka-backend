<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Api\Public\Concerns\ResolvesLocale;
use App\Http\Controllers\Controller;
use App\Services\Api\Public\StudioService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StudioController extends Controller
{
    use ResolvesLocale;

    public function __construct(private readonly StudioService $studioService)
    {
    }

    /**
     * Return localized studio details.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $locale = $this->localeFrom($request);

        return response()->json([
            'data' => $this->studioService->details($locale),
            'meta' => [
                'locale' => $locale,
            ],
        ]);
    }
}
