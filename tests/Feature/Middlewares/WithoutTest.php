<?php

declare(strict_types=1);

use Tests\Constants\LocaleValue;

test('without locale passing', function () {
    $foo = 'test';

    getJson(route('clean', compact('foo')))
        ->assertSuccessful()
        ->assertJsonPath($foo, LocaleValue::TranslationFrench);

    assertEventNotDispatched();
});
