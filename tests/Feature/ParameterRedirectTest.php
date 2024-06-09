<?php

declare(strict_types=1);

use Tests\Constants\LocaleValue;

use function Pest\Laravel\getJson;

test('main locale', function (string $locale) {
    getJson(route('via.parameter.redirect', compact('locale')))
        ->assertSuccessful()
        ->assertJsonPath('message', LocaleValue::TranslationFrench);
})->with('main-locales');

test('aliased locale', function (string $locale) {
    getJson(route('via.parameter.redirect', compact('locale')))
        ->assertSuccessful()
        ->assertJsonPath('message', LocaleValue::TranslationGerman);
})->with('aliased-locales');

test('empty locale', function (int|string|null $locale) {
    getJson(route('via.parameter.redirect', compact('locale')))
        ->assertRedirectToRoute('via.parameter.redirect', ['locale' => LocaleValue::LocaleMain]);
})->with('empty-locales');

test('not named', function (int|string|null $locale) {
    getJson(url('not-named/redirect/' . $locale))
        ->assertSuccessful()
        ->assertJsonPath('message', LocaleValue::TranslationFrench);
})->with('empty-locales');
