<?php

declare(strict_types=1);

use LaravelLang\Routes\Helpers\Route as RouteName;
use Tests\Constants\LocaleValue;

test('group: facade', function () {
    $foo = 'test';

    expect(route('via.group.facade', compact('foo')))
        ->toBeString()
        ->toBe('http://localhost/group/facade/test');

    expect(route(RouteName::prefix() . 'via.group.facade', [
        'locale' => LocaleValue::LocaleMain,
        'foo'    => $foo,
    ]))->toBeString()->toBe('http://localhost/fr/group/facade/test');
});

test('group: macro', function () {
    $foo = 'test';

    expect(route('via.group.macro', compact('foo')))
        ->toBeString()
        ->toBe('http://localhost/group/macro/test');

    expect(route(RouteName::prefix() . 'via.group.macro', [
        'locale' => LocaleValue::LocaleMain,
        'foo'    => $foo,
    ]))->toBeString()->toBe('http://localhost/fr/group/macro/test');
});
