<?php

declare(strict_types=1);

namespace LaravelLang\Routes;

use Closure;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use LaravelLang\Routes\Facades\LocalizationRoute;

class LangRoutesServiceProvider extends BaseServiceProvider
{
    public function boot(): void
    {
        Route::macro(
            name: 'localizedGroup',
            macro: static fn (Closure $callback) => LocalizationRoute::group($callback)
        );
    }
}
