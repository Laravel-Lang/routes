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
                    ->get('path/{time}/{locale?}', $this->jsonResponse())
                    ->name('via.parameter');

                app('router')
                    ->middleware(ParameterRedirectLocale::class)
                    ->get('redirect/{time}/{locale?}', $this->jsonResponse())
                    ->name('via.parameter.redirect');

                app('router')
                    ->middleware(ParameterRedirectLocale::class)
                    ->get('not-named/redirect/{time}/{locale?}', $this->jsonResponse());

                app('router')
                    ->middleware(HeaderLocale::class)
                    ->get('header/{time}', $this->jsonResponse())
                    ->name('via.header');

                app('router')
                    ->middleware(CookiesLocale::class)
                    ->get('cookie/{time}', $this->jsonResponse())
                    ->name('via.cookie');

                app('router')
                    ->middleware(SessionLocale::class)
                    ->get('session/{time}', $this->jsonResponse())
                    ->name('via.session');
            });
    }

    protected function jsonResponse(): Closure
    {
        // return fn (Request $request) => response()->json([
        //    $request->route()->parameter('locale'),
        // ]);

        return fn (int $time) => response()->json([
            'message' => __(LocaleValue::TranslationKey),
            'time'    => $time,
        ]);
    }
}