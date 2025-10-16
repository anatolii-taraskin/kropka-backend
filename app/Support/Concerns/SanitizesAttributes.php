<?php

namespace App\Support\Concerns;

trait SanitizesAttributes
{
    protected function sanitizeString(string $value): string
    {
        return trim($value);
    }

    protected function sanitizeNullableString(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $trimmed = trim($value);

        return $trimmed === '' ? null : $trimmed;
    }

    protected function sanitizeBoolean(mixed $value, bool $default = false): bool
    {
        if ($value === null) {
            return $default;
        }

        $filtered = filter_var($value, FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE);

        return $filtered ?? (bool) $value;
    }
}
