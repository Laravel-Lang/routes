<?php

declare(strict_types=1);

namespace LaravelLang\Routes;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use LaravelLang\Config\Services\RouteLangConfig;
use LaravelLang\Exceptions\InvalidLocaleInputException;
use LaravelLang\LocaleNames\LocaleData;
use LaravelLang\Locales\Facades\Locales;
use LaravelLang\Routes\Services\RouteService;

use function app;
use function array_merge;
use function is_string;
use function route;
use function strlen;
use function substr;

if (! function_exists('\LaravelLang\Routes\localizedRoute')) {
    /**
     * @param  array<array-key, mixed>  $parameters
     */
    function localizedRoute(string $route, array $parameters = [], bool $absolute = true): string
    {
        $config = app()->make(RouteLangConfig::class);

        $locale = $config->name->parameter;
        $prefix = $config->routeNamePrefix();

        $value = $parameters[$locale] ??= Locales::fallback();

        if (! Locales::acceptsInput($value)) {
            throw InvalidLocaleInputException::forRouteParameter();
        }

        $namedRoute      = Route::getRoutes()->getByName($route);
        $parentName      = $namedRoute?->getAction(RouteService::ParentNameAttribute);
        $parent          = is_string($parentName) ? $parentName : '';
        $suffix          = substr($route, strlen($parent));
        $localizedPrefix = $namedRoute?->getAction(RouteService::LocalizedPrefixAttribute);

        if ($config->hide && Locales::isInstalled($value) && Locales::toAlias($value) === Locales::fallback()) {
            unset($parameters[$locale]);

            if (is_string($localizedPrefix)) {
                $defaultRoute = $parent . Str::after($suffix, $localizedPrefix);
                $route        = Route::has($defaultRoute) ? $defaultRoute : $route;
            }

            return route($route, $parameters, $absolute);
        }

        if ($value instanceof LocaleData) {
            $parameters[$locale] = $value->alias;
        }

        $name = match (true) {
            is_string($localizedPrefix) => $route,
            is_string($parentName)      => $parent . $prefix . $suffix,
            default                     => Str::start($route, $prefix),
        };
        $route = Route::has($name) ? $name : $route;

        return route($route, array_merge([
            $locale => Locales::current(),
        ], $parameters), $absolute);
    }
}
