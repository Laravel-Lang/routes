<?php

namespace Tests;

use Closure;
use LaravelLang\Config\Enums\Name;
use LaravelLang\Config\ServiceProvider as ConfigServiceProvider;
use LaravelLang\Locales\ServiceProvider as LocalesServiceProvider;
use LaravelLang\Routes\Facades\LocalizationRoute;
use LaravelLang\Routes\Middlewares\LocalizationByCookie;
use LaravelLang\Routes\Middlewares\LocalizationByHeader;
use LaravelLang\Routes\Middlewares\LocalizationByModel;
use LaravelLang\Routes\Middlewares\LocalizationByParameter;
use LaravelLang\Routes\Middlewares\LocalizationByParameterWithRedirect;
use LaravelLang\Routes\Middlewares\LocalizationBySession;
use LaravelLang\Routes\ServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Tests\Concerns\Locales;
use Tests\Constants\LocaleValue;

abstract class TestCase extends BaseTestCase
{
    use Locales;

    protected function getPackageProviders($app): array
    {
        return [
            LocalesServiceProvider::class,
            ConfigServiceProvider::class,
            ServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        /** @var \Illuminate\Config\Repository $config */
        $config = $app['config'];

        $config->set('app.locale', LocaleValue::LocaleMain);

        $config->set('auth.guards.foo', [
            'driver'   => 'session',
            'provider' => 'users',
        ]);

        $config->set(Name::Shared() . '.aliases', [
            LocaleValue::LocaleAliasParent => LocaleValue::LocaleAlias,
        ]);
    }

    protected function defineDatabaseMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
    }

    /**
     * Define routes setup.
     *
     * @param  \Illuminate\Routing\Router  $router
     *
     * @return void
     */
    protected function defineRoutes($router): void
    {
        $router
            ->middleware('web')
            ->group(function () use ($router) {
                $router
                    ->middleware(LocalizationByParameter::class)
                    ->get('path/{foo}/{locale?}', $this->jsonResponse())
                    ->name('via.parameter');

                $router
                    ->middleware(LocalizationByParameterWithRedirect::class)
                    ->get('redirect/{foo}/{locale?}', $this->jsonResponse())
                    ->name('via.parameter.redirect');

                $router
                    ->middleware(LocalizationByParameterWithRedirect::class)
                    ->get('not-named/redirect/{foo}/{locale?}', $this->jsonResponse());

                $router
                    ->middleware(LocalizationByHeader::class)
                    ->get('header/{foo}', $this->jsonResponse())
                    ->name('via.header');

                $router
                    ->middleware(LocalizationByCookie::class)
                    ->get('cookie/{foo}', $this->jsonResponse())
                    ->name('via.cookie');

                $router
                    ->middleware(LocalizationBySession::class)
                    ->get('session/{foo}', $this->jsonResponse())
                    ->name('via.session');

                $router
                    ->middleware(LocalizationByModel::class)
                    ->get('model/default/{foo}', $this->jsonResponse())
                    ->name('via.model.default');

                $router
                    ->middleware(LocalizationByModel::class . ':foo')
                    ->get('model/guard/{foo}', $this->jsonResponse())
                    ->name('via.model.guard');

                $router
                    ->middleware([
                        LocalizationByParameter::class,
                        LocalizationByParameterWithRedirect::class,
                        LocalizationByHeader::class,
                        LocalizationByCookie::class,
                        LocalizationBySession::class,
                        LocalizationByModel::class,
                    ])
                    ->get('clean/{foo}', $this->jsonResponse())
                    ->name('clean');
            });

        $router->localizedGroup(function () use ($router) {
            $router
                ->middleware('web')
                ->get('group/macro/{foo}', $this->jsonResponse())
                ->name('via.group.macro');
        });

        LocalizationRoute::group(function () use ($router) {
            $router
                ->middleware('web')
                ->get('group/facade/{foo}', $this->jsonResponse())
                ->name('via.group.facade');
        });
    }

    protected function jsonResponse(): Closure
    {
        return fn (string $foo) => response()->json([
            $foo => __(LocaleValue::TranslationKey),
        ]);
    }
}
