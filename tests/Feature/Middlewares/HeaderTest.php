<?php

declare(strict_types=1);

use LaravelLang\Config\Facades\Config;
use Tests\Constants\LocaleValue;

use function Pest\Laravel\getJson;

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

    $translation = $locale === 'en_US'
        ? LocaleValue::TranslationKey
        : LocaleValue::TranslationFrench;

    getJson(route('via.header', compact('foo')), [
        Config::shared()->routes->names->header => $locale,
    ])
        ->assertSuccessful()
        ->assertJsonPath($foo, $translation);

    $locale === 'en_US'
        ? assertEventDispatched()
        : assertEventNotDispatched();
})->with('unknown-locales');

test('multiple accept-language', function () {
    $foo = 'test';

    getJson(route('via.header', compact('foo')), [
        Config::shared()->routes->names->header => 'fr-FR,fr;q=0.8,de',
    ])
        ->assertSuccessful()
        ->assertJsonPath($foo, LocaleValue::TranslationFrench);

    assertEventDispatched();
});

test('custom header', function () {
    config(['localization.routes.names.header' => 'X-Custom-Lang']);

    $foo = 'test';

    getJson(route('via.header', compact('foo')), [
        'X-Custom-Lang' => 'fr-FR,fr;q=0.8,de',
    ])
        ->assertSuccessful()
        ->assertJsonPath($foo, LocaleValue::TranslationFrench);

    assertEventDispatched();
});
