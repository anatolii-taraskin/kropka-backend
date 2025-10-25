<?php

namespace App\Actions\Admin\Equipment;

use App\Http\Requests\Admin\Equipment\EquipmentRequest;
use App\Models\Equipment;
use App\Services\SortOrderService;
use Illuminate\Support\Facades\Storage;

class EquipmentManager
{
    public function __construct(private readonly SortOrderService $sortOrderService)
    {
    }

    public function create(EquipmentRequest $request): Equipment
    {
        $data = $request->sanitized();
        $data['sort'] = $this->sortOrderService->nextSortValue(Equipment::class);

        if ($request->hasFile('photo')) {
            $data['photo_path'] = $request->file('photo')->store('equipment', 'public');
        }

        return Equipment::create($data);
    }

    public function update(EquipmentRequest $request, Equipment $equipment): Equipment
    {
        $data = $request->sanitized();

        if ($request->hasFile('photo')) {
            $newPhotoPath = $request->file('photo')->store('equipment', 'public');
            $this->deletePhoto($equipment->photo_path);
            $data['photo_path'] = $newPhotoPath;
        }

        $equipment->update($data);

        return $equipment;
    }

    public function delete(Equipment $equipment): void
    {
        $this->deletePhoto($equipment->photo_path);

        $equipment->delete();
    }

    private function deletePhoto(?string $path): void
    {
        if (! $path) {
            return;
        }

        Storage::disk('public')->delete($path);
    }
}

