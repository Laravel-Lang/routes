<?php

namespace Tests;

use LaravelLang\Locales\ServiceProvider as LocalesServiceProvider;
use LaravelLang\Routes\ServiceProvider as RoutesServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            RoutesServiceProvider::class,
            LocalesServiceProvider::class,
        ];
    }
}
