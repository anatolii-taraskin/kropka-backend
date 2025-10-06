<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

/**
 * Middleware registered under the "set.locale" alias that reads the
 * `{locale}` route parameter, validates it against the configured locales,
 * and sets the application locale accordingly.
 */
class SetLocaleFromRoute
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $supported = config('app.supported_locales', []);
        $locale = $request->route('locale');

        if (! is_string($locale) || ! in_array($locale, $supported, true)) {
            abort(404);
        }

        App::setLocale($locale);
        $request->attributes->set('resolved_locale', $locale);

        return $next($request);
    }
}
