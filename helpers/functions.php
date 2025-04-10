<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use LaravelLang\Config\Facades\Config;

if (! function_exists('localizedRoute')) {
    function localizedRoute(
        string $route,
        array $parameters = [],
        bool $absolute = true,
        bool $redirectDefault = false
    ): string {
        $currentLocale = app()->getLocale();
        $defaultLocale = config('app.locale');

        if ($redirectDefault && $currentLocale === $defaultLocale) {
            return route($route, $parameters, $absolute);
        }

        $config = Config::shared()->routes;
        $name = Str::start($route, $config->namePrefix);

        return Route::has($name)
            ? route($name, [$config->names->parameter => $currentLocale] + $parameters, $absolute)
            : route($route, $parameters, $absolute);
    }
}
