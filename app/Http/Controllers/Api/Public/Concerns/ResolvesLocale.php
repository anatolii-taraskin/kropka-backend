<?php

namespace App\Http\Controllers\Api\Public\Concerns;

use Illuminate\Http\Request;

trait ResolvesLocale
{
    protected function localeFrom(Request $request): string
    {
        $locale = $request->attributes->get('resolved_locale');

        if (is_string($locale) && $locale !== '') {
            return strtolower($locale);
        }

        return strtolower(app()->getLocale());
    }
}
