<?php

namespace App\Services\Api\Public;

use App\Models\StudioInfo;
use Illuminate\Support\Arr;

class StudioService
{
    public function __construct(private readonly StudioInfo $studioInfo)
    {
    }

    public function details(string $locale): array
    {
        $entries = $this->studioInfo->newQuery()->pluck('value', 'property');

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
