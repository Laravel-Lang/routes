<?php

declare(strict_types=1);

namespace Tests\Concerns;

use Closure;
use Illuminate\Http\Request;
use LaravelLang\Routes\Middlewares\ParameterLocale;
use LaravelLang\Routes\Middlewares\ParameterRedirectLocale;
use Tests\Constants\LocaleValue;

use function app;
use function response;

/** @mixin \Tests\Concerns\Locales */
trait Routes
{
    public function setUpRoutes(): void
    {
        app('router')
            ->middleware(ParameterLocale::class)
            ->get('path/{locale?}', $this->jsonResponse())
            ->name('via.parameter');

        app('router')
            ->middleware(ParameterRedirectLocale::class)
            ->get('redirect/{locale?}', $this->jsonResponse())
            ->name('via.parameter.redirect');

        app('router')
            ->middleware(ParameterRedirectLocale::class)
            ->get('not-named/redirect/{locale?}', $this->jsonResponse());
    }

    protected function jsonResponse(): Closure
    {
        // return fn (Request $request) => response()->json([
        //    $request->route()->parameter('locale'),
        // ]);

        return fn () => response()->json([
            'message' => __(LocaleValue::TranslationKey),
        ]);
    }
}
