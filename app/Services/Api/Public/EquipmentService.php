<?php

namespace App\Services\Api\Public;

use App\Models\Equipment;

class EquipmentService
{
    public function __construct(private readonly Equipment $equipment)
    {
    }

    public function list(string $locale): array
    {
        return $this->equipment->newQuery()
            ->where('is_active', true)
            ->orderBy('sort')
            ->orderBy('id')
            ->get()
            ->map(function (Equipment $equipment) use ($locale) {
                return [
                    'id' => $equipment->id,
                    'name' => $equipment->localizedName($locale),
                    'description' => $equipment->localizedDescription($locale),
                    'photo_url' => $equipment->photoUrl(),
                    'sort' => $equipment->sort,
                ];
            })
            ->values()
            ->all();
    }
}
