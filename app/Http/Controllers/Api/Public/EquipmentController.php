<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Api\Public\Concerns\ResolvesLocale;
use App\Http\Controllers\Controller;
use App\Models\Equipment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    use ResolvesLocale;

    /**
     * Return the list of published equipment in the requested locale.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $locale = $this->localeFrom($request);

        $equipment = Equipment::query()
            ->where('is_active', true)
            ->orderBy('sort')
            ->orderBy('id')
            ->get()
            ->map(function (Equipment $item) use ($locale) {
                return [
                    'id' => $item->id,
                    'name' => $item->localizedName($locale),
                    'description' => $item->localizedDescription($locale),
                    'photo_url' => $item->photoUrl(),
                    'sort' => $item->sort,
                ];
            });

        return response()->json([
            'data' => $equipment,
            'meta' => [
                'locale' => $locale,
            ],
        ]);
    }
}
