<?php

declare(strict_types=1);

namespace LaravelLang\Routes\Services;

use Illuminate\Routing\UrlGenerator as BaseGenerator;
use Illuminate\Support\Str;
use LaravelLang\Config\Facades\Config;

use function app;
use function array_merge;
use function is_null;

class UrlGenerator extends BaseGenerator
{
    public function route($name, $parameters = [], $absolute = true): string
    {
        if ($this->isLocalizedGroupRoute($name) && ! is_null($route = $this->routes->getByName($name))) {
            return $this->toRoute($route, $this->resolveLocalizedParameters($parameters), $absolute);
        }

        return parent::route($name, $parameters, $absolute);
    }

    protected function isLocalizedGroupRoute(string $name): bool
    {
        return Str::startsWith($name, $this->localizedRoutePrefix());
    }

    protected function resolveLocalizedParameters(array $parameters): array
    {
        return array_merge([
            $this->localizeRouteParameter() => app()->getLocale(),
        ], $parameters);
    }

    protected function localizedRoutePrefix(): string
    {
        return Config::shared()->routes->namePrefix;
    }

    protected function localizeRouteParameter(): string
    {
        return Config::shared()->routes->names->parameter;
    }
}
