<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use LaravelLang\Config\Services\RouteConfig;
use LaravelLang\Locales\Facades\Locales;

if (! function_exists('localizedRoute')) {
    function localizedRoute(string $route, array $parameters = [], bool $absolute = true): string
    {
        $config = app()->make(RouteConfig::class);

        $locale = $config->name->parameter;
        $prefix = $config->prefix;

        $parameters[$locale] ??= Locales::raw()->getFallback();

        $hide = $config->hide
            || ! Locales::isInstalled($locale)
            || Locales::raw()->get($locale) === Locales::raw()->getFallback();

        if ($hide) {
            unset($parameters[$locale]);

            return route($route, $parameters, $absolute);
        }

        $name  = Str::start($route, $prefix);
        $route = Route::has($name) ? $name : $route;

        return route($route, array_merge([
            $locale => app()->getLocale(),
        ], $parameters), $absolute);
    }
}
