<?php

declare(strict_types=1);

use Tests\Constants\LocaleValue;

use function Pest\Laravel\getJson;

test('main locale', function (string $locale) {
    $time = time();

    getJson(route('via.parameter', compact('time', 'locale')))
        ->assertSuccessful()
        ->assertJsonPath('message', LocaleValue::TranslationFrench)
        ->assertJsonPath('time', $time);
})->with('main-locales');

test('aliased locale', function (string $locale) {
    $time = time();

    getJson(route('via.parameter', compact('time', 'locale')))
        ->assertSuccessful()
        ->assertJsonPath('message', LocaleValue::TranslationGerman)
        ->assertJsonPath('time', $time);
})->with('aliased-locales');

test('empty locale', function (int|string|null $locale) {
    $time = time();

    getJson(route('via.parameter', compact('time', 'locale')))
        ->assertSuccessful()
        ->assertJsonPath('message', LocaleValue::TranslationFrench)
        ->assertJsonPath('time', $time);
})->with('empty-locales');
