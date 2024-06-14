<?php

declare(strict_types=1);

use LaravelLang\Routes\Helpers\Name;

test('group', function () {
    expect(app('router')->has('via.group'))->toBeTrue();
    expect(app('router')->has(Name::parameter() . '.via.group'))->toBeTrue();
});
