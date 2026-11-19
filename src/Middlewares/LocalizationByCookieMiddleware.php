<?php

declare(strict_types=1);

namespace LaravelLang\Routes\Middlewares;

use Illuminate\Http\Request;

class LocalizationByCookieMiddleware extends Middleware
{
    protected function detect(Request $request): int|string|null
    {
        return $request->cookie(
            $this->routeConfig->name->cookie
        );
    }
}
