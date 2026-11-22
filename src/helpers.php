<?php

declare(strict_types=1);

namespace LaravelLang\Routes;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use LaravelLang\Config\Services\RouteLangConfig;
use LaravelLang\LocaleList\Locale;
use LaravelLang\Locales\Facades\Locales;
use LaravelLang\NativeLocaleNames\LocaleData;
use TypeError;

use function app;
use function array_merge;
use function is_string;
use function route;

if (! function_exists('\LaravelLang\Routes\localizedRoute')) {
    /**
     * @param  array<array-key, mixed>  $parameters
     */
    function localizedRoute(string $route, array $parameters = [], bool $absolute = true): string
    {
        $config = app()->make(RouteLangConfig::class);

        $locale = $config->name->parameter;
        $prefix = $config->prefix;

        $value = $parameters[$locale] ??= Locales::fallback();

        if (! $value instanceof Locale && ! $value instanceof LocaleData && ! is_string($value)) {
            throw new TypeError('The locale route parameter must be a Locale, a LocaleData, or a string');
        }

        if ($config->hide && Locales::isInstalled($value) && Locales::toAlias($value) === Locales::fallback()) {
            unset($parameters[$locale]);

            return route($route, $parameters, $absolute);
        }

        $name  = Str::start($route, $prefix);
        $route = Route::has($name) ? $name : $route;

        return route($route, array_merge([
            $locale => Locales::current(),
        ], $parameters), $absolute);
    }
}
