<?php

namespace App\Support\Concerns;

trait NormalizesLocale
{
    /**
     * Resolve the given locale or fall back to the configured defaults.
     */
    protected function resolveLocale(?string $locale = null): string
    {
        $locale = $locale
            ?? app()->getLocale()
            ?? config('app.fallback_locale')
            ?? config('app.locale');

        return strtolower((string) $locale);
    }

    /**
     * Retrieve the supported locales for the application.
     *
     * @return array<int, string>
     */
    protected function supportedLocales(): array
    {
        $locales = config('app.supported_locales', []);

        return array_values(array_unique(array_map(
            static fn ($value) => strtolower((string) $value),
            array_filter($locales, static fn ($value) => is_string($value) && $value !== '')
        )));
    }

    /**
     * Determine the fallback locales for the provided locale.
     *
     * @return array<int, string>
     */
    protected function fallbackLocales(string $locale): array
    {
        $locales = $this->supportedLocales();

        if ($locales === []) {
            return [];
        }

        $locale = strtolower($locale);

        if (! in_array($locale, $locales, true)) {
            return $locales;
        }

        $index = array_search($locale, $locales, true);

        $fallbacks = array_slice($locales, $index + 1);
        $fallbacks = array_merge($fallbacks, array_slice($locales, 0, $index));

        return array_values(array_unique($fallbacks));
    }
}

