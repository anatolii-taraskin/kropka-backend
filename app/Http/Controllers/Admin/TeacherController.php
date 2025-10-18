<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Services\SortOrderService;
use App\Support\Concerns\SanitizesAttributes;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class TeacherController extends Controller
{
    use SanitizesAttributes;

    public function __construct(private readonly SortOrderService $sortOrderService)
    {
    }

    /**
     * Display the list of teachers.
     */
    public function index(): View
    {
        $teachers = Teacher::query()
            ->orderBy('sort')
            ->orderBy('id')
            ->get();

        return view('admin.teachers.index', [
            'teachers' => $teachers,
        ]);
    }

    /**
     * Show the form for creating a new teacher entry.
     */
    public function create(): View
    {
        return view('admin.teachers.create');
    }

    /**
     * Store a newly created teacher entry.
     */
    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make(
            $request->all(),
            $this->rules(),
            [],
            $this->attributes(),
        );

        $validated = $validator->validateWithBag('createTeacher');

        $data = $this->prepareData($validated);

        $data['sort'] = $this->sortOrderService->nextSortValue(Teacher::class);

        if ($request->hasFile('photo')) {
            $data['photo_path'] = $request->file('photo')->store('teachers', 'public');
        }

        Teacher::create($data);

        return redirect()
            ->route('admin.teachers.index')
            ->with('status', 'teacher-created');
    }

    /**
     * Show the form for editing the specified teacher entry.
     */
    public function edit(Teacher $teacher): View
    {
        return view('admin.teachers.edit', [
            'teacher' => $teacher,
        ]);
    }

    /**
     * Update the specified teacher entry.
     */
    public function update(Request $request, Teacher $teacher): RedirectResponse
    {
        $validator = Validator::make(
            $request->all(),
            $this->rules(),
            [],
            $this->attributes(),
        );

        $validated = $validator->validateWithBag('updateTeacher' . $teacher->id);

        $data = $this->prepareData($validated);

        if ($request->hasFile('photo')) {
            $newPhotoPath = $request->file('photo')->store('teachers', 'public');
            $this->deletePhoto($teacher->photo_path);
            $data['photo_path'] = $newPhotoPath;
        }

        $teacher->update($data);

        return redirect()
            ->route('admin.teachers.index')
            ->with('status', 'teacher-updated');
    }

    /**
     * Remove the specified teacher entry.
     */
    public function destroy(Teacher $teacher): RedirectResponse
    {
        $this->deletePhoto($teacher->photo_path);

        $teacher->delete();

        return redirect()
            ->route('admin.teachers.index')
            ->with('status', 'teacher-deleted');
    }

    /**
     * Update the sort order for teachers.
     */
    public function reorder(Request $request): JsonResponse
    {
        $data = $request->validate([
            'order' => ['required', 'array'],
            'order.*' => ['integer', 'distinct', 'exists:teachers,id'],
        ]);

        $ids = $data['order'];

        $this->sortOrderService->reorder(Teacher::class, $ids);

        return response()->json(['status' => 'ok']);
    }

    /**
     * Validation rules for teacher forms.
     */
    private function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'telegram_url' => ['nullable', 'url', 'max:255'],
            'photo' => ['nullable', 'image', 'max:5120'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * Human readable attribute names.
     */
    private function attributes(): array
    {
        return [
            'name' => __('admin.teachers.fields.name'),
            'description' => __('admin.teachers.fields.description'),
            'telegram_url' => __('admin.teachers.fields.telegram_url'),
            'photo' => __('admin.teachers.fields.photo'),
            'is_active' => __('admin.teachers.fields.is_active'),
        ];
    }

    /**
     * Prepare sanitized data for persistence.
     */
    private function prepareData(array $input): array
    {
        return [
            'name' => $this->sanitizeString($input['name'] ?? ''),
            'description' => $this->sanitizeNullableString($input['description'] ?? null),
            'telegram_url' => $this->sanitizeNullableString($input['telegram_url'] ?? null),
            'is_active' => $this->sanitizeBoolean($input['is_active'] ?? null),
        ];
    }

    private function deletePhoto(?string $path): void
    {
        if (! $path) {
            return;
        }

        Storage::disk('public')->delete($path);
    }
}
