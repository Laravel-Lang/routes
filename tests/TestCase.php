<?php

namespace Tests;

use LaravelLang\Config\Enums\Name;
use LaravelLang\Config\ServiceProvider as ConfigServiceProvider;
use LaravelLang\Locales\ServiceProvider as LocalesServiceProvider;
use LaravelLang\Routes\ServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Tests\Concerns\Locales;
use Tests\Concerns\Routes;
use Tests\Constants\LocaleValue;

abstract class TestCase extends BaseTestCase
{
    use Routes;
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
}
