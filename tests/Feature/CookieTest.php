<?php

declare(strict_types=1);

use LaravelLang\Config\Facades\Config;
use Tests\Constants\LocaleValue;

use function Pest\Laravel\withCookie;

test('main locale', function (string $locale) {
    withCookie(Config::shared()->routes->names->cookie, $locale)
        ->getJson(route('via.cookie'))
        ->assertSuccessful()
        ->assertJsonPath('message', LocaleValue::TranslationFrench);
})->with('main-locales');

test('aliased locale', function (string $locale) {
    withCookie(Config::shared()->routes->names->cookie, $locale)
        ->getJson(route('via.cookie'))
        ->assertSuccessful()
        ->assertJsonPath('message', LocaleValue::TranslationGerman);
})->with('aliased-locales');

test('empty locale', function (int|string|null $locale) {
    withCookie(Config::shared()->routes->names->cookie, $locale)
        ->getJson(route('via.cookie'))
        ->assertSuccessful()
        ->assertJsonPath('message', LocaleValue::TranslationFrench);
})->with('empty-locales');
