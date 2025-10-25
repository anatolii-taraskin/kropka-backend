<?php

namespace App\Services\Api\Public;

use App\Models\StudioRule;

class StudioRuleService
{
    public function __construct(private readonly StudioRule $studioRule)
    {
    }

    public function list(string $locale): array
    {
        return $this->studioRule->newQuery()
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
            })
            ->values()
            ->all();
    }
}
