<?php

declare(strict_types=1);

namespace LaravelLang\Routes\Services;

use Closure;
use Illuminate\Support\Facades\Route as BaseRoute;
use LaravelLang\Config\Services\RouteLangConfig;

class RouteService
{
    public function __construct(
        protected RouteLangConfig $routeConfig,
    ) {}

    public function group(Closure $callback): void
    {
        $this->defaultGroup($callback);
        $this->prefixedGroup($callback);
    }

    protected function defaultGroup(Closure $callback): void
    {
        BaseRoute::middleware(
            $this->routeConfig->middlewares->default
        )->group($callback);
    }

    protected function prefixedGroup(Closure $callback): void
    {
        BaseRoute::prefix('{' . $this->routeConfig->name->parameter . '}')
            ->name($this->routeConfig->prefix)
            ->middleware($this->routeConfig->middlewares->prefix)
            ->group($callback);
    }
}
