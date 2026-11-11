<?php

declare(strict_types=1);

namespace LaravelLang\Routes\Services;

use Closure;
use Illuminate\Support\Facades\Route as BaseRoute;
use LaravelLang\Config\Services\RouteConfig;
use LaravelLang\Routes\Concerns\RouteParameters;
use LaravelLang\Routes\Helpers\Route as RouteName;

class Route
{
    use RouteParameters;

    public function __construct(
        protected RouteConfig $routeConfig,
    ) {}

    public function group(Closure $callback): void
    {
        $this->defaultGroup($callback);
        $this->prefixedGroup($callback);
    }

    protected function defaultGroup(Closure $callback): void
    {
        BaseRoute::middleware(
            $this->routeConfig->groupMiddleware->default
        )->group($callback);
    }

    protected function prefixedGroup(Closure $callback): void
    {
        BaseRoute::prefix('{' . $this->names()->parameter . '}')
            ->name(RouteName::prefix())
            ->middleware($this->routeConfig->groupMiddleware->prefix)
            ->group($callback);
    }
}
