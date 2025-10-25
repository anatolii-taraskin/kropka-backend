<?php

namespace App\Actions\Admin;

use App\Services\SortOrderService;

class ReorderRecords
{
    public function __construct(private readonly SortOrderService $sortOrderService)
    {
    }

    /**
     * @param  class-string  $modelClass
     */
    public function handle(string $modelClass, array $ids): void
    {
        $this->sortOrderService->reorder($modelClass, $ids);
    }
}

