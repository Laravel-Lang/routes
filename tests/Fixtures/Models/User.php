<?php

declare(strict_types=1);

namespace Tests\Fixtures\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = [
        'locale',
    ];
}
