<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Equipment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class EquipmentController extends Controller
{
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

        $data['sort'] = min((Equipment::max('sort') ?? 0) + 1, 255);

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

        DB::transaction(function () use ($ids) {
            foreach ($ids as $index => $id) {
                Equipment::whereKey($id)->update(['sort' => min($index + 1, 255)]);
            }
        });

        return response()->json(['status' => 'ok']);
    }

    /**
     * Validation rules for equipment forms.
     */
    private function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
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
            'name' => __('admin.equipment.fields.name'),
            'description' => __('admin.equipment.fields.description'),
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
            'name' => $this->sanitizeString($input['name'] ?? ''),
            'description' => $this->sanitizeNullableString($input['description'] ?? null),
            'is_active' => array_key_exists('is_active', $input) ? (bool) $input['is_active'] : false,
        ];
    }

    private function sanitizeString(string $value): string
    {
        return trim($value);
    }

    private function sanitizeNullableString(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $trimmed = trim($value);

        return $trimmed === '' ? null : $trimmed;
    }

    private function deletePhoto(?string $path): void
    {
        if (! $path) {
            return;
        }

        Storage::disk('public')->delete($path);
    }
}
