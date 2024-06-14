<?php

declare(strict_types=1);

use LaravelLang\Config\Enums\Name as NameEnum;
use LaravelLang\Routes\Helpers\Name;

test('parameter', function () {
    expect(Name::parameter())->toBe(
        config(NameEnum::Shared() . '.routes.names.parameter')
    );
});

test('header', function () {
    expect(Name::header())->toBe(
        config(NameEnum::Shared() . '.routes.names.header')
    );
});

test('cookie', function () {
    expect(Name::cookie())->toBe(
        config(NameEnum::Shared() . '.routes.names.cookie')
    );
});

test('session', function () {
    expect(Name::session())->toBe(
        config(NameEnum::Shared() . '.routes.names.session')
    );
});
