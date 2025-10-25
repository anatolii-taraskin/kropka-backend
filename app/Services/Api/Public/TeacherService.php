<?php

namespace App\Services\Api\Public;

use App\Models\Teacher;

class TeacherService
{
    public function __construct(private readonly Teacher $teacher)
    {
    }

    public function list(string $locale): array
    {
        return $this->teacher->newQuery()
            ->where('is_active', true)
            ->orderBy('sort')
            ->orderBy('id')
            ->get()
            ->map(function (Teacher $teacher) use ($locale) {
                return [
                    'id' => $teacher->id,
                    'name' => $teacher->localizedName($locale),
                    'description' => $teacher->localizedDescription($locale),
                    'telegram_url' => $teacher->telegram_url,
                    'photo_url' => $teacher->photoUrl(),
                    'sort' => $teacher->sort,
                ];
            })
            ->values()
            ->all();
    }
}
