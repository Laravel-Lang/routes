<?php

declare(strict_types=1);

use Tests\Constants\LocaleValue;

use function Pest\Laravel\getJson;

test('with guard', function () {
    $foo = 'test';

    getJson(route('via.model.guard', compact('foo')))
        ->assertSuccessful()
        ->assertJsonPath($foo, LocaleValue::TranslationFrench);

    assertEventNotDispatched();
});

test('without guard', function () {
    $foo = 'test';

    getJson(route('via.model.default', compact('foo')))
        ->assertSuccessful()
        ->assertJsonPath($foo, LocaleValue::TranslationFrench);

    assertEventNotDispatched();
});
