<?php

declare(strict_types=1);

namespace LaravelLang\Routes\Middlewares;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use LaravelLang\Locales\Facades\Locales;

class LocalizationByHeader extends Middleware
{
    protected function detect(Request $request): bool|float|int|string|null
    {
        if (! $value = $request->header($this->names()->header)) {
            return null;
        }

        if (! Locales::isInstalled($this->strBefore($value, [',', ';']))) {
            return null;
        }

        return $value;
    }

    protected function strBefore(string $value, array $search): string
    {
        foreach ($search as $needle) {
            if (Str::contains($value, $needle)) {
                return Str::before($value, $needle);
            }
        }

        return $value;
    }
}
