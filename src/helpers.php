<?php

declare(strict_types=1);

namespace LaravelLang\Routes;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use LaravelLang\Config\Services\AliasConfig;
use LaravelLang\Config\Services\RouteConfig;
use LaravelLang\LocaleList\Locale;
use LaravelLang\Locales\Facades\Locales;
use LaravelLang\NativeLocaleNames\LocaleData;

if (! function_exists('\LaravelLang\Routes\localizedRoute')) {
    function localizedRoute(string $route, array $parameters = [], bool $absolute = true): string
    {
        $config = app()->make(RouteConfig::class);
        $alias  = app()->make(AliasConfig::class);

        $locale = $config->name->parameter;
        $prefix = $config->prefix;

        $parameters[$locale] ??= Locales::fallback();

        $hide = static function (Locale|LocaleData|string $locale) use ($config, $alias): bool {
            if (! $config->hide || ! Locales::isInstalled($locale)) {
                return false;
            }

            return $alias->to($locale) === Locales::fallback();
        };

        if ($hide($parameters[$locale])) {
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
