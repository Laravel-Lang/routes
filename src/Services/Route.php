<?php

declare(strict_types=1);

namespace LaravelLang\Routes\Services;

use Closure;
use Illuminate\Support\Facades\Route as BaseRoute;
use LaravelLang\Config\Facades\Config;
use LaravelLang\Routes\Concerns\RouteParameters;
use LaravelLang\Routes\Helpers\Route as RouteName;

class Route
{
    use RouteParameters;

    public function group(Closure $callback): void
    {
        $this->defaultGroup($callback);
        $this->prefixedGroup($callback);
    }

    protected function defaultGroup(Closure $callback): void
    {
        BaseRoute::middleware(
            Config::shared()->routes->group->middleware->default
        )->group($callback);
    }

    protected function prefixedGroup(Closure $callback): void
    {
        BaseRoute::prefix('{' . $this->names()->parameter . '}')
            ->name(RouteName::prefix())
            ->middleware(
                Config::shared()->routes->group->middleware->prefix
            )->group($callback);
    }
}
