<?php

namespace App\Http\Controllers\Api\Public\Concerns;

use App\Support\Concerns\NormalizesLocale;
use Illuminate\Http\Request;

trait ResolvesLocale
{
    use NormalizesLocale;

    protected function localeFrom(Request $request): string
    {
        $locale = $request->attributes->get('resolved_locale');

        if (is_string($locale) && $locale !== '') {
            return $this->resolveLocale($locale);
        }

        return $this->resolveLocale();
    }
}
