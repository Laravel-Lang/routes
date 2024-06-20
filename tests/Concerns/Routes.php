<?php

declare(strict_types=1);

namespace Tests\Concerns;

use Closure;
use LaravelLang\Routes\Facades\LocalizationRoute;
use LaravelLang\Routes\Middlewares\LocalizationByCookie;
use LaravelLang\Routes\Middlewares\LocalizationByHeader;
use LaravelLang\Routes\Middlewares\LocalizationByModel;
use LaravelLang\Routes\Middlewares\LocalizationByParameter;
use LaravelLang\Routes\Middlewares\LocalizationByParameterWithRedirect;
use LaravelLang\Routes\Middlewares\LocalizationBySession;
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
                    ->middleware(LocalizationByParameter::class)
                    ->get('path/{foo}/{locale?}', $this->jsonResponse())
                    ->name('via.parameter');

                app('router')
                    ->middleware(LocalizationByParameterWithRedirect::class)
                    ->get('redirect/{foo}/{locale?}', $this->jsonResponse())
                    ->name('via.parameter.redirect');

                app('router')
                    ->middleware(LocalizationByParameterWithRedirect::class)
                    ->get('not-named/redirect/{foo}/{locale?}', $this->jsonResponse());

                app('router')
                    ->middleware(LocalizationByHeader::class)
                    ->get('header/{foo}', $this->jsonResponse())
                    ->name('via.header');

                app('router')
                    ->middleware(LocalizationByCookie::class)
                    ->get('cookie/{foo}', $this->jsonResponse())
                    ->name('via.cookie');

                app('router')
                    ->middleware(LocalizationBySession::class)
                    ->get('session/{foo}', $this->jsonResponse())
                    ->name('via.session');

                app('router')
                    ->middleware(LocalizationByModel::class)
                    ->get('model/default/{foo}', $this->jsonResponse())
                    ->name('via.model.default');

                app('router')
                    ->middleware(LocalizationByModel::class . ':foo')
                    ->get('model/guard/{foo}', $this->jsonResponse())
                    ->name('via.model.guard');

                app('router')
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

        app('router')->localizedGroup(function () {
            app('router')
                ->middleware('web')
                ->get('group/macro/{foo}', $this->jsonResponse())
                ->name('via.group.macro');
        });

        LocalizationRoute::group(function () {
            app('router')
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
