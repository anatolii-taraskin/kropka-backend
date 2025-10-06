<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;

/**
 * Middleware registered under the "resolve.locale" alias that negotiates the
 * best locale for the current request based on query parameters, headers, and
 * configured fallbacks.
 */
class ResolveRequestLocale
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ?string $context = null)
    {
        $supported = $this->supportedLocales($context);
        $locale = $this->determineLocale($request, $supported);

        App::setLocale($locale);
        $request->attributes->set('resolved_locale', $locale);

        return $next($request);
    }

    /**
     * Determine the locale for the request.
     */
    protected function determineLocale(Request $request, array $supported): string
    {
        $queryLocale = $this->normalizeLocale($request->query('lang'));
        if ($queryLocale && in_array($queryLocale, $supported, true)) {
            return $queryLocale;
        }

        $preferred = $request->getPreferredLanguage($supported);

        if (is_string($preferred) && in_array($preferred, $supported, true)) {
            return $preferred;
        }

        $fallback = config('app.fallback_locale');

        return in_array($fallback, $supported, true)
            ? $fallback
            : Arr::first($supported, null, config('app.locale'));
    }

    /**
     * Prepare the list of supported locales for the given context.
     */
    protected function supportedLocales(?string $context): array
    {
        $supported = array_values(array_unique(config('app.supported_locales', [])));

        if ($context === 'admin') {
            $adminLocales = array_values(array_intersect($supported, config('app.admin_locales', [])));
            if (! empty($adminLocales)) {
                $supported = $adminLocales;
            }
        }

        if (empty($supported)) {
            $supported = [config('app.locale')];
        }

        return $supported;
    }

    /**
     * Normalize the requested locale value.
     */
    protected function normalizeLocale(mixed $locale): ?string
    {
        if (! is_string($locale) || $locale === '') {
            return null;
        }

        $normalized = strtolower(str_replace('_', '-', $locale));

        return explode('-', $normalized)[0];
    }
}
