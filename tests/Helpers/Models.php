<?php

declare(strict_types=1);

use Tests\Fixtures\Models\User;

function fakeUser(int|string|null $locale = null): User
{
    return User::create([
        'locale' => $locale,
    ]);
}
