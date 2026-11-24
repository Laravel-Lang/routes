<?php

declare(strict_types=1);

namespace LaravelLang\Routes\Middlewares;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use LaravelLang\Locales\Facades\Locales;
use LaravelLang\Routes\Services\RouteService;

use function is_string;
use function strlen;
use function substr;

class LocalizationByParameterPrefixMiddlewareMiddleware extends LocalizationByParameterWithRedirectMiddleware
{
    /** @return array<array-key, mixed> */
    protected function parameters(Request $request): array
    {
        return Arr::except($request->route()?->parameters() ?? [], [
            $this->routeConfig->name->parameter,
        ]);
    }

    protected function routeName(Request $request): ?string
    {
        $route = $request->route();

        if ($route && ($name = $route->getName())) {
            $parent = $route->getAction(RouteService::ParentNameAttribute);
            $parent = is_string($parent) ? $parent : '';
            $prefix = $route->getAction(RouteService::LocalizedPrefixAttribute);
            $prefix = is_string($prefix) ? $prefix : $this->routePrefix();

            return $parent . Str::after(substr($name, strlen($parent)), $prefix);
        }

        return null;
    }

    protected function invalidParameter(Request $request): ?bool
    {
        return ! $this->hasParameter($request)
            || ! $this->trim($this->detect($request))
            || ! $this->isInstalled($this->detect($request))
            || $this->isDefault($this->detect($request));
    }

    protected function isDefault(bool|float|int|string|null $locale): bool
    {
        if ($this->allowRedirect()) {
            $locale = $locale === null ? null : (string) $locale;

            return Locales::info($locale)->alias === Locales::current();
        }

        return false;
    }

    protected function routePrefix(): string
    {
        return $this->routeConfig->routeNamePrefix();
    }

    protected function allowRedirect(): bool
    {
        return $this->routeConfig->redirect;
    }
}
