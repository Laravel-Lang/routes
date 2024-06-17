<?php

declare(strict_types=1);

use LaravelLang\Routes\Helpers\Route as RouteName;
use Tests\Constants\LocaleValue;

test('group', function () {
    $foo = 'test';

    expect(route('via.group', compact('foo')))
        ->toBeString()
        ->toBe('http://localhost/group/test');

    expect(route(RouteName::prefix() . 'via.group', [
        'locale' => LocaleValue::LocaleMain,
        'foo'    => $foo,
    ]))->toBeString()->toBe('http://localhost/fr/group/test');
});
