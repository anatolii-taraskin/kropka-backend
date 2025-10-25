<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Api\Public\Concerns\ResolvesLocale;
use App\Http\Controllers\Controller;
use App\Services\Api\Public\StudioRuleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StudioRuleController extends Controller
{
    use ResolvesLocale;

    public function __construct(private readonly StudioRuleService $studioRuleService)
    {
    }

    /**
     * Return the list of published studio rules in the requested locale.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $locale = $this->localeFrom($request);

        return response()->json([
            'data' => $this->studioRuleService->list($locale),
            'meta' => [
                'locale' => $locale,
            ],
        ]);
    }
}
