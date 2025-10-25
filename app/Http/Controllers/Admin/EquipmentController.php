<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Admin\Equipment\EquipmentManager;
use App\Actions\Admin\ReorderRecords;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Equipment\ReorderEquipmentRequest;
use App\Http\Requests\Admin\Equipment\StoreEquipmentRequest;
use App\Http\Requests\Admin\Equipment\UpdateEquipmentRequest;
use App\Models\Equipment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EquipmentController extends Controller
{
    public function __construct(
        private readonly EquipmentManager $equipmentManager,
        private readonly ReorderRecords $reorderRecords,
    )
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
    public function store(StoreEquipmentRequest $request): RedirectResponse
    {
        $this->equipmentManager->create($request);

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
    public function update(UpdateEquipmentRequest $request, Equipment $equipment): RedirectResponse
    {
        $this->equipmentManager->update($request, $equipment);

        return redirect()
            ->route('admin.equipment.index')
            ->with('status', 'equipment-updated');
    }

    /**
     * Remove the specified equipment entry.
     */
    public function destroy(Equipment $equipment): RedirectResponse
    {
        $this->equipmentManager->delete($equipment);

        return redirect()
            ->route('admin.equipment.index')
            ->with('status', 'equipment-deleted');
    }

    /**
     * Update the sort order for equipment tiles.
     */
    public function reorder(ReorderEquipmentRequest $request): JsonResponse
    {
        $this->reorderRecords->handle(Equipment::class, $request->ids());

        return response()->json(['status' => 'ok']);
    }
}
