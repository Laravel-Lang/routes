<?php

declare(strict_types=1);

namespace LaravelLang\Routes;

use Closure;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use LaravelLang\Routes\Facades\LocalizationRoute;
use LaravelLang\Routes\Services\UrlGenerator;

class ServiceProvider extends BaseServiceProvider
{
    public function boot(): void
    {
        $this->registerGroup();
        $this->urlGenerator();
    }

    protected function registerGroup(): void
    {
        Route::macro('localizedGroup', fn (Closure $callback) => LocalizationRoute::group($callback));
    }

    protected function urlGenerator(): void
    {
        $this->app->singleton('url', function ($app) {
            $routes = $app['router']->getRoutes();

            $app->instance('routes', $routes);

            return new UrlGenerator(
                $routes,
                $app->rebinding('request', $this->requestRebinder()),
                $app['config']['app.asset_url']
            );
        });
    }

    protected function requestRebinder(): Closure
    {
        return fn (Application $app, Request $request) => $app['url']->setRequest($request);
    }
}
