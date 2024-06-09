<?php

declare(strict_types=1);

use LaravelLang\Config\Facades\Config;
use Tests\Constants\LocaleValue;

use function Pest\Laravel\getJson;

test('main locale', function (string $locale) {
    getJson(route('via.header'), [
        Config::shared()->routes->names->header => $locale,
    ])
        ->assertSuccessful()
        ->assertJsonPath('message', LocaleValue::TranslationFrench);
})->with('main-locales');

test('aliased locale', function (string $locale) {
    getJson(route('via.header'), [
        Config::shared()->routes->names->header => $locale,
    ])
        ->assertSuccessful()
        ->assertJsonPath('message', LocaleValue::TranslationGerman);
})->with('aliased-locales');

test('empty locale', function (int|string|null $locale) {
    getJson(route('via.header'), [
        Config::shared()->routes->names->header => $locale,
    ])
        ->assertSuccessful()
        ->assertJsonPath('message', LocaleValue::TranslationFrench);
})->with('empty-locales');
