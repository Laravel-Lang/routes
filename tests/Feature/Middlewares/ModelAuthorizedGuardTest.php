<?php

declare(strict_types=1);

use Tests\Constants\LocaleValue;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;

test('main locale', function () {
    actingAs(fakeUser(), 'foo');

    $foo = 'test';

    getJson(route('via.model.guard', compact('foo')))
        ->assertSuccessful()
        ->assertJsonPath($foo, LocaleValue::TranslationFrench);

    assertEventNotDispatched();
});

test('aliased locale', function (string $locale) {
    actingAs(fakeUser($locale));

    $foo = 'test';

    getJson(route('via.model.guard', compact('foo', 'locale')))
        ->assertSuccessful()
        ->assertJsonPath($foo, LocaleValue::TranslationGerman);

    assertEventDispatched();
})->with('aliased-locales');

test('empty locale', function (int|string|null $locale) {
    actingAs(fakeUser($locale));

    $foo = 'test';

    getJson(route('via.model.guard', compact('foo', 'locale')))
        ->assertSuccessful()
        ->assertJsonPath($foo, LocaleValue::TranslationFrench);

    assertEventNotDispatched();
})->with('empty-locales');

test('uninstalled locale', function (string $locale) {
    actingAs(fakeUser($locale));

    $foo = 'test';

    getJson(route('via.model.guard', compact('foo', 'locale')))
        ->assertSuccessful()
        ->assertJsonPath($foo, LocaleValue::TranslationFrench);

    assertEventDispatched();
})->with('uninstalled-locales');

test('unknown locale', function (int|string $locale) {
    actingAs(fakeUser($locale));

    $foo = 'test';

    getJson(route('via.model.guard', compact('foo', 'locale')))
        ->assertSuccessful()
        ->assertJsonPath($foo, LocaleValue::TranslationFrench);

    assertEventDispatched();
})->with('unknown-locales');
