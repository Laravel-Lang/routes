<?php

declare(strict_types=1);

use LaravelLang\Config\Facades\Config;
use Tests\Constants\LocaleValue;

use function Pest\Laravel\withSession;

test('main locale', function (string $locale) {
    $foo = 'test';

    withSession([Config::shared()->routes->names->session => $locale])
        ->getJson(route('via.session', compact('foo')))
        ->assertSuccessful()
        ->assertJsonPath($foo, LocaleValue::TranslationFrench);

    assertEventDispatched();
})->with('main-locales');

test('aliased locale', function (string $locale) {
    $foo = 'test';

    withSession([Config::shared()->routes->names->session => $locale])
        ->getJson(route('via.session', compact('foo')))
        ->assertSuccessful()
        ->assertJsonPath($foo, LocaleValue::TranslationGerman);

    assertEventDispatched();
})->with('aliased-locales');

test('empty locale', function (int|string|null $locale) {
    $foo = 'test';

    withSession([Config::shared()->routes->names->session => $locale])
        ->getJson(route('via.session', compact('foo')))
        ->assertSuccessful()
        ->assertJsonPath($foo, LocaleValue::TranslationFrench);

    assertEventNotDispatched();
})->with('empty-locales');

test('uninstalled locale', function (string $locale) {
    $foo = 'test';

    withSession([Config::shared()->routes->names->session => $locale])
        ->getJson(route('via.session', compact('foo')))
        ->assertSuccessful()
        ->assertJsonPath($foo, LocaleValue::TranslationFrench);

    assertEventDispatched();
})->with('uninstalled-locales');

test('unknown locale', function (int|string $locale) {
    $foo = 'test';

    withSession([Config::shared()->routes->names->session => $locale])
        ->getJson(route('via.session', compact('foo')))
        ->assertSuccessful()
        ->assertJsonPath($foo, LocaleValue::TranslationFrench);

    assertEventDispatched();
})->with('unknown-locales');
