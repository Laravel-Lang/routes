<?php

declare(strict_types=1);

use LaravelLang\Config\Facades\Config;
use Tests\Constants\LocaleValue;

test('main locale', function (string $locale) {
    $foo = 'test';

    getJson(route('via.header', compact('foo')), [
        Config::shared()->routes->names->header => $locale,
    ])
        ->assertSuccessful()
        ->assertJsonPath($foo, LocaleValue::TranslationFrench);

    assertEventDispatched();
})->with('main-locales');

test('aliased locale', function (string $locale) {
    $foo = 'test';

    getJson(route('via.header', compact('foo')), [
        Config::shared()->routes->names->header => $locale,
    ])
        ->assertSuccessful()
        ->assertJsonPath($foo, LocaleValue::TranslationGerman);

    assertEventDispatched();
})->with('aliased-locales');

test('empty locale', function (int|string|null $locale) {
    $foo = 'test';

    getJson(route('via.header', compact('foo')), [
        Config::shared()->routes->names->header => $locale,
    ])
        ->assertSuccessful()
        ->assertJsonPath($foo, LocaleValue::TranslationFrench);

    assertEventNotDispatched();
})->with('empty-locales');

test('uninstalled locale', function (string $locale) {
    $foo = 'test';

    getJson(route('via.header', compact('foo')), [
        Config::shared()->routes->names->header => $locale,
    ])
        ->assertSuccessful()
        ->assertJsonPath($foo, LocaleValue::TranslationFrench);

    assertEventNotDispatched();
})->with('uninstalled-locales');

test('unknown locale', function (int|string $locale) {
    $foo = 'test';

    getJson(route('via.header', compact('foo')), [
        Config::shared()->routes->names->header => $locale,
    ])
        ->assertSuccessful()
        ->assertJsonPath($foo, LocaleValue::TranslationFrench);

    assertEventNotDispatched();
})->with('unknown-locales');

test('multiple accept-language', function () {
    $foo = 'test';

    getJson(route('via.header', compact('foo')), [
        Config::shared()->routes->names->header => "fr-FR,fr;q=0.8,de",
    ])
        ->assertSuccessful()
        ->assertJsonPath($foo, LocaleValue::TranslationFrench);

    assertEventDispatched();
});
