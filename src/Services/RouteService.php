<?php

declare(strict_types=1);

namespace LaravelLang\Routes\Services;

use Closure;
use Illuminate\Support\Facades\Route as BaseRoute;
use LaravelLang\Config\Services\RouteLangConfig;

use function end;
use function is_array;

class RouteService
{
    public const string ParentNameAttribute      = 'laravelLangParentRouteName';
    public const string LocalizedPrefixAttribute = 'laravelLangLocalizedRoutePrefix';

    public function __construct(
        protected RouteLangConfig $routeConfig,
    ) {}

    public function group(Closure $callback): void
    {
        $groups = BaseRoute::getGroupStack();
        $parent = end($groups);

        BaseRoute::group([
            self::ParentNameAttribute => is_array($parent) ? ($parent['as'] ?? '') : '',
        ], function () use ($callback): void {
            $this->defaultGroup($callback);
            $this->prefixedGroup($callback);
        });
    }

    protected function defaultGroup(Closure $callback): void
    {
        BaseRoute::middleware(
            $this->routeConfig->middlewares->default
        )->group($callback);
    }

    protected function prefixedGroup(Closure $callback): void
    {
        $prefix = $this->routeConfig->routeNamePrefix();

        BaseRoute::group([
            'prefix'                       => '{' . $this->routeConfig->name->parameter . '}',
            'as'                           => $prefix,
            'middleware'                   => $this->routeConfig->middlewares->prefix,
            self::LocalizedPrefixAttribute => $prefix,
        ], $callback);
    }
}
