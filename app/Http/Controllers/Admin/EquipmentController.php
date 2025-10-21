<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Equipment;
use App\Services\SortOrderService;
use App\Support\Concerns\SanitizesAttributes;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class EquipmentController extends Controller
{
    use SanitizesAttributes;

    public function __construct(private readonly SortOrderService $sortOrderService)
    {
    }

    /**
     * Display the list of equipment items.
     */
    public function index(): View
    {
        $equipment = Equipment::query()
            ->orderBy('sort')
            ->orderBy('id')
            ->get();

        return view('admin.equipment.index', [
            'equipment' => $equipment,
        ]);
    }

    /**
     * Show the form for creating a new equipment entry.
     */
    public function create(): View
    {
        return view('admin.equipment.create');
    }

    /**
     * Store a newly created equipment entry.
     */
    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make(
            $request->all(),
            $this->rules(),
            [],
            $this->attributes(),
        );

        $validated = $validator->validateWithBag('createEquipment');

        $data = $this->prepareData($validated);

        $data['sort'] = $this->sortOrderService->nextSortValue(Equipment::class);

        if ($request->hasFile('photo')) {
            $data['photo_path'] = $request->file('photo')->store('equipment', 'public');
        }

        Equipment::create($data);

        return redirect()
            ->route('admin.equipment.index')
            ->with('status', 'equipment-created');
    }

    /**
     * Show the form for editing the specified equipment entry.
     */
    public function edit(Equipment $equipment): View
    {
        return view('admin.equipment.edit', [
            'equipment' => $equipment,
        ]);
    }

    /**
     * Update the specified equipment entry.
     */
    public function update(Request $request, Equipment $equipment): RedirectResponse
    {
        $validator = Validator::make(
            $request->all(),
            $this->rules(),
            [],
            $this->attributes(),
        );

        $validated = $validator->validateWithBag('updateEquipment' . $equipment->id);

        $data = $this->prepareData($validated);

        if ($request->hasFile('photo')) {
            $newPhotoPath = $request->file('photo')->store('equipment', 'public');
            $this->deletePhoto($equipment->photo_path);
            $data['photo_path'] = $newPhotoPath;
        }

        $equipment->update($data);

        return redirect()
            ->route('admin.equipment.index')
            ->with('status', 'equipment-updated');
    }

    /**
     * Remove the specified equipment entry.
     */
    public function destroy(Equipment $equipment): RedirectResponse
    {
        $this->deletePhoto($equipment->photo_path);

        $equipment->delete();

        return redirect()
            ->route('admin.equipment.index')
            ->with('status', 'equipment-deleted');
    }

    /**
     * Update the sort order for equipment tiles.
     */
    public function reorder(Request $request): JsonResponse
    {
        $data = $request->validate([
            'order' => ['required', 'array'],
            'order.*' => ['integer', 'distinct', 'exists:equipment,id'],
        ]);

        $ids = $data['order'];

        $this->sortOrderService->reorder(Equipment::class, $ids);

        return response()->json(['status' => 'ok']);
    }

    /**
     * Validation rules for equipment forms.
     */
    private function rules(): array
    {
        return [
            'name_ru' => ['required', 'string', 'max:255'],
            'name_en' => ['required', 'string', 'max:255'],
            'description_ru' => ['nullable', 'string'],
            'description_en' => ['nullable', 'string'],
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
            'name_ru' => __('admin.equipment.fields.name_ru'),
            'name_en' => __('admin.equipment.fields.name_en'),
            'description_ru' => __('admin.equipment.fields.description_ru'),
            'description_en' => __('admin.equipment.fields.description_en'),
            'photo' => __('admin.equipment.fields.photo'),
            'is_active' => __('admin.equipment.fields.is_active'),
        ];
    }

    /**
     * Prepare sanitized data for persistence.
     */
    private function prepareData(array $input): array
    {
        return [
            'name_ru' => $this->sanitizeString($input['name_ru'] ?? ''),
            'name_en' => $this->sanitizeString($input['name_en'] ?? ''),
            'description_ru' => $this->sanitizeNullableString($input['description_ru'] ?? null),
            'description_en' => $this->sanitizeNullableString($input['description_en'] ?? null),
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
