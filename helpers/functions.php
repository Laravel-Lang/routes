<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use LaravelLang\Config\Facades\Config;
use LaravelLang\Locales\Facades\Locales;

if (! function_exists('localizedRoute')) {
    function localizedRoute(
        string $route,
        array $parameters = [],
        bool $absolute = true,
    ): string {
        if (Config::shared()->routes->hide && Locales::raw()->getFallback() === Locales::raw()->getCurrent()) {
            return route($route, $parameters, $absolute);
        }

        $locale = Config::shared()->routes->names->parameter;
        $prefix = Config::shared()->routes->namePrefix;

        $name = Str::start($route, $prefix);

        if (! Route::has($name)) {
            return route($route, $parameters, $absolute);
        }

        return route($name, array_merge([
            $locale => app()->getLocale(),
        ], $parameters), $absolute);
    }
}