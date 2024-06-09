<?php

declare(strict_types=1);

use LaravelLang\Config\Facades\Config;
use Tests\Constants\LocaleValue;

use function Pest\Laravel\withSession;

test('main locale', function (string $locale) {
    $time = time();

    withSession([Config::shared()->routes->names->session => $locale])
        ->getJson(route('via.session', compact('time')))
        ->assertSuccessful()
        ->assertJsonPath('message', LocaleValue::TranslationFrench)
        ->assertJsonPath('time', $time);
})->with('main-locales');

test('aliased locale', function (string $locale) {
    $time = time();

    withSession([Config::shared()->routes->names->session => $locale])
        ->getJson(route('via.session', compact('time')))
        ->assertSuccessful()
        ->assertJsonPath('message', LocaleValue::TranslationGerman)
        ->assertJsonPath('time', $time);
})->with('aliased-locales');

test('empty locale', function (int|string|null $locale) {
    $time = time();

    withSession([Config::shared()->routes->names->session => $locale])
        ->getJson(route('via.session', compact('time')))
        ->assertSuccessful()
        ->assertJsonPath('message', LocaleValue::TranslationFrench)
        ->assertJsonPath('time', $time);
})->with('empty-locales');

test('uninstalled locale', function (string $locale) {
    $time = time();

    withSession([Config::shared()->routes->names->session => $locale])
        ->getJson(route('via.session', compact('time')))
        ->assertSuccessful()
        ->assertJsonPath('message', LocaleValue::TranslationFrench)
        ->assertJsonPath('time', $time);
})->with('uninstalled-locales');

test('unknown locale', function (int|string $locale) {
    $time = time();

    withSession([Config::shared()->routes->names->session => $locale])
        ->getJson(route('via.session', compact('time')))
        ->assertSuccessful()
        ->assertJsonPath('message', LocaleValue::TranslationFrench)
        ->assertJsonPath('time', $time);
})->with('unknown-locales');
