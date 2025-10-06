<?php

declare(strict_types=1);

use Tests\Constants\LocaleValue;

test('main locale', function (string $locale) {
    $foo = 'test';

    getJson(route('via.parameter.redirect', compact('foo', 'locale')))
        ->assertSuccessful()
        ->assertJsonPath($foo, LocaleValue::TranslationFrench);

    assertEventDispatched();
})->with('main-locales');

test('aliased locale', function (string $locale) {
    $foo = 'test';

    getJson(route('via.parameter.redirect', compact('foo', 'locale')))
        ->assertSuccessful()
        ->assertJsonPath($foo, LocaleValue::TranslationGerman);

    assertEventDispatched();
})->with('aliased-locales');

test('empty locale', function (int|string|null $locale) {
    $foo = 'test';

    getJson(route('via.parameter.redirect', compact('foo', 'locale')))
        ->assertRedirectToRoute('via.parameter.redirect', [
            'locale' => LocaleValue::LocaleMain,
            'foo'    => $foo,
        ]);

    assertEventNotDispatched();
})->with('empty-locales');

test('uninstalled locale', function (string $locale) {
    $foo = 'test';

    getJson(route('via.parameter.redirect', compact('foo', 'locale')))
        ->assertRedirectToRoute('via.parameter.redirect', [
            'locale' => LocaleValue::LocaleMain,
            'foo'    => $foo,
        ]);

    assertEventNotDispatched();
})->with('uninstalled-locales');

test('unknown locale', function (int|string $locale) {
    $foo = 'test';

    getJson(route('via.parameter.redirect', compact('foo', 'locale')))
        ->assertRedirectToRoute('via.parameter.redirect', [
            'locale' => LocaleValue::LocaleMain,
            'foo'    => $foo,
        ]);

    assertEventNotDispatched();
})->with('unknown-locales');

test('not named', function (int|string|null $locale) {
    $foo = 'test';

    getJson(url('not-named/redirect/' . $foo . '/' . $locale))
        ->assertSuccessful()
        ->assertJsonPath($foo, LocaleValue::TranslationFrench);

    assertEventNotDispatched();
})->with('empty-locales');
