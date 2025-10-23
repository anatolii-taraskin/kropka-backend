<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Api\Public\Concerns\ResolvesLocale;
use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    use ResolvesLocale;

    /**
     * Return the list of published teachers in the requested locale.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $locale = $this->localeFrom($request);

        $teachers = Teacher::query()
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
            });

        return response()->json([
            'data' => $teachers,
            'meta' => [
                'locale' => $locale,
            ],
        ]);
    }
}
