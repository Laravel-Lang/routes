<?php

namespace Tests;

use LaravelLang\Config\Enums\Name;
use LaravelLang\Config\ServiceProvider as ConfigServiceProvider;
use LaravelLang\Locales\ServiceProvider as LocalesServiceProvider;
use LaravelLang\Routes\ServiceProvider as RoutesServiceProvider;
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
            RoutesServiceProvider::class,
            LocalesServiceProvider::class,
            ConfigServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        /** @var \Illuminate\Config\Repository $config */
        $config = $app['config'];

        $config->set('app.locale', LocaleValue::LocaleMain);

        $config->set(Name::Shared->value . '.aliases', [
            LocaleValue::LocaleAliasParent => LocaleValue::LocaleAlias,
        ]);
    }
}
