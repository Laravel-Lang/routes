<?php

declare(strict_types=1);

namespace LaravelLang\Routes\Services;

use LaravelLang\Locales\Facades\Locales;

class Resolver
{
    public static function locale(bool|float|int|string|null $locale): string
    {
        return Locales::get($locale)->code ?? Locales::getDefault()->code;
    }
}
