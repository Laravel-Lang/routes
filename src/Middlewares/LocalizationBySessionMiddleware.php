<?php

declare(strict_types=1);

namespace LaravelLang\Routes\Middlewares;

use Illuminate\Http\Request;

class LocalizationBySessionMiddleware extends Middleware
{
    protected function detect(Request $request): int|string|null
    {
        if (! $request->hasSession()) {
            return null;
        }

        return $request->getSession()->get(
            $this->routeConfig->name->session
        );
    }
}
