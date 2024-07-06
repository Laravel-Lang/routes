<?php

declare(strict_types=1);

use LaravelLang\Config\Facades\Config;
use Tests\Constants\LocaleValue;

test('localized route generation', function () {
    $name     = Config::shared()->routes->namePrefix;
    $locale   = LocaleValue::LocaleMain;
    $fallback = LocaleValue::LocaleAliasParent;

    expect(route($name . 'via.group.facade', ['foo' => 'bar']))
        ->toEndWith("localhost/$locale/group/facade/bar");

    expect(route($name . 'via.group.macro', ['foo' => 'bar', 'locale' => $fallback]))
        ->toEndWith("localhost/$fallback/group/macro/bar");
});

test('non-localized route generation', function () {
    expect(route('via.group.facade', ['foo' => 'bar']))
        ->toEndWith('localhost/group/facade/bar');

    expect(route('via.group.macro', ['foo' => 'bar']))
        ->toEndWith('localhost/group/macro/bar');
});
