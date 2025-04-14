<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use LaravelLang\Config\Facades\Config;
use LaravelLang\Routes\Helpers\Route as RouteHelper;

if (! function_exists('localizedRoute')) {
    function localizedRoute(string $route, array $parameters = [], bool $absolute = true): string
    {
        $locale = Config::shared()->routes->names->parameter;
        $prefix = Config::shared()->routes->namePrefix;

        if (RouteHelper::hidingFallback()) {
            unset($parameters[$locale]);

            return route($route, $parameters, $absolute);
        }

        $name = Str::start($route, $prefix);
        $route = Route::has($name) ? $name : $route;

        return route($route, array_merge([
            $locale => app()->getLocale(),
        ], $parameters), $absolute);
    }
}
