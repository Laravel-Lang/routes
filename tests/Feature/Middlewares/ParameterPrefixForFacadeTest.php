<?php

declare(strict_types=1);

use LaravelLang\Config\Enums\Name;
use LaravelLang\Config\Facades\Config;
use LaravelLang\Routes\Helpers\Route as RouteName;
use Tests\Constants\LocaleValue;

use function Pest\Laravel\getJson;

test('main without prefix', function () {
    $foo = 'test';

    getJson(route('via.group.facade', compact('foo')), [
        Config::shared()->routes->names->header => ''
    ])
        ->assertSuccessful()
        ->assertJsonPath($foo, LocaleValue::TranslationFrench);
});

test('main locale with enabled redirect', function (string $locale) {
    config()->set(Name::Shared() . '.routes.redirect_default', true);

    $foo = 'test';

    getJson(route(RouteName::prefix() . 'via.group.facade', compact('foo', 'locale')))
        ->assertRedirectToRoute('via.group.facade', [
            'foo' => $foo,
        ]);

    assertEventNotDispatched();
})->with('main-locales');

test('main locale with disabled redirect', function (string $locale) {
    config()->set(Name::Shared() . '.routes.redirect_default', false);

    $foo = 'test';

    getJson(route(RouteName::prefix() . 'via.group.facade', compact('foo', 'locale')), [
        Config::shared()->routes->names->header => ''
    ])
        ->assertSuccessful()
        ->assertJsonPath($foo, LocaleValue::TranslationFrench);

    assertEventDispatched();
})->with('main-locales');

test('aliased locale', function (string $locale) {
    $foo = 'test';

    getJson(route(RouteName::prefix() . 'via.group.facade', compact('foo', 'locale')), [
        Config::shared()->routes->names->header => ''
    ])
        ->assertSuccessful()
        ->assertJsonPath($foo, LocaleValue::TranslationGerman);

    assertEventDispatched();
})->with('aliased-locales');

test('uninstalled locale', function (string $locale) {
    $foo = 'test';

    getJson(route(RouteName::prefix() . 'via.group.facade', compact('foo', 'locale')))
        ->assertRedirectToRoute('via.group.facade', [
            'foo' => $foo,
        ]);

    assertEventNotDispatched();
})->with('uninstalled-locales');

test('unknown locale', function (int|string $locale) {
    $foo = 'test';

    getJson(route(RouteName::prefix() . 'via.group.facade', compact('foo', 'locale')))
        ->assertRedirectToRoute('via.group.facade', [
            'foo' => $foo,
        ]);

    assertEventNotDispatched();
})->with('unknown-locales');
