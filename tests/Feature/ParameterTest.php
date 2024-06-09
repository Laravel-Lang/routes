<?php

declare(strict_types=1);

use Tests\Constants\LocaleValue;

use function Pest\Laravel\getJson;

test('main locale', function (string $locale) {
    getJson(route('via.parameter', compact('locale')))
        ->assertSuccessful()
        ->assertJsonPath('message', LocaleValue::TranslationFrench);
})->with('main-locales');

test('aliased locale', function (string $locale) {
    getJson(route('via.parameter', compact('locale')))
        ->assertSuccessful()
        ->assertJsonPath('message', LocaleValue::TranslationGerman);
})->with('aliased-locales');

test('empty locale', function (string|int|null $locale) {
    getJson(route('via.parameter', compact('locale')))
        ->assertSuccessful()
        ->assertJsonPath('message', LocaleValue::TranslationFrench);
})->with('empty-locales');
