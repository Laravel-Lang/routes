<?php

declare(strict_types=1);

use LaravelLang\Config\Facades\Config;
use Tests\Constants\LocaleValue;

use function Pest\Laravel\getJson;

test('without locale passing', function () {
    $foo = 'test';

    getJson(route('clean', compact('foo')), [
        Config::shared()->routes->names->header => ''
    ])
        ->assertSuccessful()
        ->assertJsonPath($foo, LocaleValue::TranslationFrench);

    assertEventNotDispatched();
});
