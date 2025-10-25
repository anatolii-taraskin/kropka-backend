<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Api\Public\Concerns\ResolvesLocale;
use App\Http\Controllers\Controller;
use App\Services\Api\Public\EquipmentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    use ResolvesLocale;

    public function __construct(private readonly EquipmentService $equipmentService)
    {
    }

    /**
     * Return the list of published equipment in the requested locale.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $locale = $this->localeFrom($request);

        return response()->json([
            'data' => $this->equipmentService->list($locale),
            'meta' => [
                'locale' => $locale,
            ],
        ]);
    }
}
