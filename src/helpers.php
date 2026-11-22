<?php

declare(strict_types=1);

namespace LaravelLang\Routes;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use LaravelLang\Config\Services\RouteLangConfig;
use LaravelLang\Exceptions\InvalidLocaleInputException;
use LaravelLang\Locales\Facades\Locales;

use function app;
use function array_merge;
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

        if (! Locales::acceptsInput($value)) {
            throw InvalidLocaleInputException::forRouteParameter();
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
