<?php

declare(strict_types=1);

namespace LaravelLang\Routes\Middlewares;

use Illuminate\Http\Request;

use function is_iterable;

class LocalizationByCookieMiddleware extends Middleware
{
    protected function detect(Request $request): int|string|null
    {
        $value = $this->getCookie($request);

        if ($value === null || is_iterable($value)) {
            return null;
        }

        return $value;
    }

    /** @return iterable<array-key, mixed>|string|null */
    protected function getCookie(Request $request): iterable|string|null
    {
        return $request->cookie(
            $this->routeConfig->name->cookie
        );
    }
}
