<?php

declare(strict_types=1);

use Tests\Constants\LocaleValue;

use function Pest\Laravel\getJson;

test('main locale', function (string $locale) {
    $foo = 'test';

    getJson(route('via.parameter.redirect', compact('foo', 'locale')))
        ->assertSuccessful()
        ->assertJsonPath($foo, LocaleValue::TranslationFrench);
})->with('main-locales');

test('aliased locale', function (string $locale) {
    $foo = 'test';

    getJson(route('via.parameter.redirect', compact('foo', 'locale')))
        ->assertSuccessful()
        ->assertJsonPath($foo, LocaleValue::TranslationGerman);
})->with('aliased-locales');

test('empty locale', function (int|string|null $locale) {
    $foo = 'test';

    getJson(route('via.parameter.redirect', compact('foo', 'locale')))
        ->assertRedirectToRoute('via.parameter.redirect', [
            'locale' => LocaleValue::LocaleMain,
            'foo'    => $foo,
        ]);
})->with('empty-locales');

test('uninstalled locale', function (string $locale) {
    $foo = 'test';

    getJson(route('via.parameter.redirect', compact('foo', 'locale')))
        ->assertRedirectToRoute('via.parameter.redirect', [
            'locale' => LocaleValue::LocaleMain,
            'foo'    => $foo,
        ]);
})->with('uninstalled-locales');

test('unknown locale', function (int|string $locale) {
    $foo = 'test';

    getJson(route('via.parameter.redirect', compact('foo', 'locale')))
        ->assertRedirectToRoute('via.parameter.redirect', [
            'locale' => LocaleValue::LocaleMain,
            'foo'    => $foo,
        ]);
})->with('unknown-locales');

test('not named', function (int|string|null $locale) {
    $foo = 'test';

    getJson(url('not-named/redirect/' . $foo . '/' . $locale))
        ->assertSuccessful()
        ->assertJsonPath($foo, LocaleValue::TranslationFrench);
})->with('empty-locales');
