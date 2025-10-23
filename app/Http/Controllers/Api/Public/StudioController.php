<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Api\Public\Concerns\ResolvesLocale;
use App\Http\Controllers\Controller;
use App\Models\StudioInfo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class StudioController extends Controller
{
    use ResolvesLocale;

    /**
     * Return localized studio details.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $locale = $this->localeFrom($request);

        $entries = StudioInfo::query()->pluck('value', 'property');

        $data = [];
        $localized = [];

        foreach ($entries as $property => $value) {
            if (preg_match('/^(?<base>.+)_(?<lang>ru|en)$/', $property, $matches)) {
                $base = $matches['base'];
                $lang = $matches['lang'];

                $localized[$base][$lang] = $value;
                continue;
            }

            $data[$property] = $value;
        }

        foreach ($localized as $property => $translations) {
            $data[$property] = $translations[$locale]
                ?? Arr::first($translations)
                ?? null;
        }

        ksort($data);

        return response()->json([
            'data' => $data,
            'meta' => [
                'locale' => $locale,
            ],
        ]);
    }
}
