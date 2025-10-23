<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Api\Public\Concerns\ResolvesLocale;
use App\Http\Controllers\Controller;
use App\Models\StudioRule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StudioRuleController extends Controller
{
    use ResolvesLocale;

    /**
     * Return the list of published studio rules in the requested locale.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $locale = $this->localeFrom($request);

        $rules = StudioRule::query()
            ->where('is_active', true)
            ->orderBy('sort')
            ->orderBy('id')
            ->get()
            ->map(function (StudioRule $rule) use ($locale) {
                return [
                    'id' => $rule->id,
                    'property' => $rule->property,
                    'text' => $rule->localizedValue($locale),
                    'sort' => $rule->sort,
                ];
            });

        return response()->json([
            'data' => $rules,
            'meta' => [
                'locale' => $locale,
            ],
        ]);
    }
}
