<?php

declare(strict_types=1);

use LaravelLang\Config\Facades\Config;
use Tests\Constants\LocaleValue;

use function Pest\Laravel\withSession;

test('main locale', function (string $locale) {
    withSession([Config::shared()->routes->names->session => $locale])
        ->getJson(route('via.session'))
        ->assertSuccessful()
        ->assertJsonPath('message', LocaleValue::TranslationFrench);
})->with('main-locales');

test('aliased locale', function (string $locale) {
    withSession([Config::shared()->routes->names->session => $locale])
        ->getJson(route('via.session'))
        ->assertSuccessful()
        ->assertJsonPath('message', LocaleValue::TranslationGerman);
})->with('aliased-locales');

test('empty locale', function (int|string|null $locale) {
    withSession([Config::shared()->routes->names->session => $locale])
        ->getJson(route('via.session'))
        ->assertSuccessful()
        ->assertJsonPath('message', LocaleValue::TranslationFrench);
})->with('empty-locales');
