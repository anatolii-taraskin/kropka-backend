<?php

namespace App\Services\Api\Public;

use App\Services\StudioInfoRepository;
use Illuminate\Support\Arr;

class StudioService
{
    public function __construct(private readonly StudioInfoRepository $repository)
    {
    }

    public function details(string $locale): array
    {
        $entries = $this->repository->all();

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
