<?php

declare(strict_types=1);

use LaravelLang\Config\Facades\Config;
use Tests\Constants\LocaleValue;

use function Pest\Laravel\withCredentials;

test('main locale', function (string $locale) {
    $foo = 'test';

    withCredentials()
        ->withCookie(Config::shared()->routes->names->cookie, $locale)
        ->getJson(route('via.cookie', compact('foo')))
        ->assertSuccessful()
        ->assertJsonPath($foo, LocaleValue::TranslationFrench);
})->with('main-locales');

test('aliased locale', function (string $locale) {
    $foo = 'test';

    withCredentials()
        ->withCookie(Config::shared()->routes->names->cookie, $locale)
        ->getJson(route('via.cookie', compact('foo')))
        ->assertSuccessful()
        ->assertJsonPath($foo, LocaleValue::TranslationGerman);
})->with('aliased-locales');

test('empty locale', function (int|string|null $locale) {
    $foo = 'test';

    withCredentials()
        ->withCookie(Config::shared()->routes->names->cookie, (string) $locale)
        ->getJson(route('via.cookie', compact('foo')))
        ->assertSuccessful()
        ->assertJsonPath($foo, LocaleValue::TranslationFrench);
})->with('empty-locales');

test('uninstalled locale', function (string $locale) {
    $foo = 'test';

    withCredentials()
        ->withCookie(Config::shared()->routes->names->cookie, $locale)
        ->getJson(route('via.cookie', compact('foo')))
        ->assertSuccessful()
        ->assertJsonPath($foo, LocaleValue::TranslationFrench);
})->with('uninstalled-locales');

test('unknown locale', function (int|string $locale) {
    $foo = 'test';

    withCredentials()
        ->withCookie(Config::shared()->routes->names->cookie, (string) $locale)
        ->getJson(route('via.cookie', compact('foo')))
        ->assertSuccessful()
        ->assertJsonPath($foo, LocaleValue::TranslationFrench);
})->with('unknown-locales');
