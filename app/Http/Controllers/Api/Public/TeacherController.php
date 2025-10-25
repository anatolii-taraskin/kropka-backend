<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Api\Public\Concerns\ResolvesLocale;
use App\Http\Controllers\Controller;
use App\Services\Api\Public\TeacherService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    use ResolvesLocale;

    public function __construct(private readonly TeacherService $teacherService)
    {
    }

    /**
     * Return the list of published teachers in the requested locale.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $locale = $this->localeFrom($request);

        return response()->json([
            'data' => $this->teacherService->list($locale),
            'meta' => [
                'locale' => $locale,
            ],
        ]);
    }
}
