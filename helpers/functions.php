<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use LaravelLang\Config\Facades\Config;
use LaravelLang\Routes\Helpers\Route as RouteHelper;

if (! function_exists('localizedRoute')) {
    function localizedRoute(
        string $route,
        array $parameters = [],
        bool $absolute = true
    ): string {
        $localeKey = Config::shared()->routes->names->parameter;

        if (RouteHelper::hidingFallback()) {
            unset($parameters[$localeKey]);
            return route($route, $parameters, $absolute);
        }

        $prefix = Config::shared()->routes->namePrefix;
        $name = Str::startsWith($route, $prefix) ? $route : Str::start($route, $prefix);
        $route = Route::has($name) ? $name : $route;

        return route($route, array_merge([
            $localeKey => app()->getLocale(),
        ], $parameters), $absolute);
    }
}