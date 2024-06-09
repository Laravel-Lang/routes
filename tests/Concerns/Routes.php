<?php

declare(strict_types=1);

namespace Tests\Concerns;

use Closure;
use LaravelLang\Routes\Middlewares\CookiesLocale;
use LaravelLang\Routes\Middlewares\HeaderLocale;
use LaravelLang\Routes\Middlewares\ParameterLocale;
use LaravelLang\Routes\Middlewares\ParameterRedirectLocale;
use LaravelLang\Routes\Middlewares\SessionLocale;
use Tests\Constants\LocaleValue;

use function app;
use function response;

/** @mixin \Tests\Concerns\Locales */
trait Routes
{
    public function setUpRoutes(): void
    {
        app('router')
            ->middleware('web')
            ->group(function () {
                app('router')
                    ->middleware(ParameterLocale::class)
                    ->get('path/{foo}/{locale?}', $this->jsonResponse())
                    ->name('via.parameter');

                app('router')
                    ->middleware(ParameterRedirectLocale::class)
                    ->get('redirect/{foo}/{locale?}', $this->jsonResponse())
                    ->name('via.parameter.redirect');

                app('router')
                    ->middleware(ParameterRedirectLocale::class)
                    ->get('not-named/redirect/{foo}/{locale?}', $this->jsonResponse());

                app('router')
                    ->middleware(HeaderLocale::class)
                    ->get('header/{foo}', $this->jsonResponse())
                    ->name('via.header');

                app('router')
                    ->middleware(CookiesLocale::class)
                    ->get('cookie/{foo}', $this->jsonResponse())
                    ->name('via.cookie');

                app('router')
                    ->middleware(SessionLocale::class)
                    ->get('session/{foo}', $this->jsonResponse())
                    ->name('via.session');
            });
    }

    protected function jsonResponse(): Closure
    {
        return fn (string $foo) => response()->json([
            $foo => __(LocaleValue::TranslationKey),
        ]);
    }
}
