<?php

namespace App\Services;

use App\Models\StudioInfo;

class StudioInfoRepository
{
    public function __construct(private readonly StudioInfo $studioInfo)
    {
    }

    public function all(): array
    {
        return $this->studioInfo->newQuery()->pluck('value', 'property')->all();
    }

    public function save(array $values, array $properties): void
    {
        foreach ($properties as $property) {
            $value = $values[$property] ?? '';
            $value = $value ?? '';

            $this->studioInfo->newQuery()->updateOrCreate(
                ['property' => $property],
                ['value' => $value]
            );
        }
    }
}
