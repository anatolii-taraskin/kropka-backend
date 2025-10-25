<?php

namespace App\Services\Api\Public;

use App\Http\Services\StudioInfoService;
use Illuminate\Support\Arr;

class StudioService
{
    public function __construct(private readonly StudioInfoService $studioInfoService)
    {
    }

    public function details(string $locale): array
    {
        $entries = $this->studioInfoService->all();

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

        return $data;
    }
}
