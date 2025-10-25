<?php

namespace App\Http\Services;

use App\Actions\Admin\ReorderRecords;
use App\Http\Requests\Admin\StudioRule\StudioRuleRequest;
use App\Models\StudioRule;
use App\Services\SortOrderService;

class StudioRuleService
{
    public function __construct(
        private readonly SortOrderService $sortOrderService,
        private readonly ReorderRecords $reorderRecords,
    ) {
    }

    public function create(StudioRuleRequest $request): StudioRule
    {
        $data = $request->sanitized();
        $data['sort'] = $this->sortOrderService->nextSortValue(StudioRule::class);
        $data['property'] = $this->generateProperty();

        return StudioRule::create($data);
    }

    public function update(StudioRuleRequest $request, StudioRule $studioRule): StudioRule
    {
        $data = $request->sanitized();

        $studioRule->update($data);

        return $studioRule;
    }

    public function delete(StudioRule $studioRule): void
    {
        $studioRule->delete();
    }

    public function reorder(array $ids): void
    {
        $this->reorderRecords->handle(StudioRule::class, $ids);
    }

    private function generateProperty(): string
    {
        $nextNumber = (StudioRule::max('id') ?? 0) + 1;

        return sprintf('rule_%02d', $nextNumber);
    }
}
