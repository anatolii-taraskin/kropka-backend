<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class SortOrderService
{
    private const MAX_SORT = 255;

    /**
     * Determine the next sort value for the given model.
     */
    public function nextSortValue(string $modelClass, string $column = 'sort'): int
    {
        $model = $this->resolveModel($modelClass);

        $maxSort = $model::max($column);

        return min((int) ($maxSort ?? 0) + 1, self::MAX_SORT);
    }

    /**
     * Persist the provided identifiers in the specified order.
     */
    public function reorder(string $modelClass, array $ids): void
    {
        $model = $this->resolveModel($modelClass);

        DB::transaction(function () use ($model, $ids) {
            foreach ($ids as $index => $id) {
                $model::whereKey($id)->update(['sort' => min($index + 1, self::MAX_SORT)]);
            }
        });
    }

    /**
     * @template T of Model
     *
     * @param  class-string<T>  $modelClass
     * @return class-string<T>
     */
    private function resolveModel(string $modelClass): string
    {
        if (! is_subclass_of($modelClass, Model::class)) {
            throw new InvalidArgumentException(sprintf('Class %s must be an instance of %s.', $modelClass, Model::class));
        }

        return $modelClass;
    }
}
