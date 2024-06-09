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
})->with('main-locales');

test('aliased locale', function (string $locale) {
    $foo = 'test';

    withSession([Config::shared()->routes->names->session => $locale])
        ->getJson(route('via.session', compact('foo')))
        ->assertSuccessful()
        ->assertJsonPath($foo, LocaleValue::TranslationGerman);
})->with('aliased-locales');

test('empty locale', function (int|string|null $locale) {
    $foo = 'test';

    withSession([Config::shared()->routes->names->session => $locale])
        ->getJson(route('via.session', compact('foo')))
        ->assertSuccessful()
        ->assertJsonPath($foo, LocaleValue::TranslationFrench);
})->with('empty-locales');

test('uninstalled locale', function (string $locale) {
    $foo = 'test';

    withSession([Config::shared()->routes->names->session => $locale])
        ->getJson(route('via.session', compact('foo')))
        ->assertSuccessful()
        ->assertJsonPath($foo, LocaleValue::TranslationFrench);
})->with('uninstalled-locales');

test('unknown locale', function (int|string $locale) {
    $foo = 'test';

    withSession([Config::shared()->routes->names->session => $locale])
        ->getJson(route('via.session', compact('foo')))
        ->assertSuccessful()
        ->assertJsonPath($foo, LocaleValue::TranslationFrench);
})->with('unknown-locales');
