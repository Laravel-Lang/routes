<?php

declare(strict_types=1);

namespace LaravelLang\Routes\Middlewares;

use Illuminate\Http\Request;
use TypeError;

use function is_int;
use function is_string;

class LocalizationBySessionMiddleware extends Middleware
{
    protected function detect(Request $request): int|string|null
    {
        if (! $request->hasSession()) {
            return null;
        }

        $locale = $request->getSession()->get(
            $this->routeConfig->name->session
        );

        if ($locale !== null && ! is_int($locale) && ! is_string($locale)) {
            throw new TypeError('The session locale must be an integer, a string, or null');
        }

        return $locale;
    }
}
