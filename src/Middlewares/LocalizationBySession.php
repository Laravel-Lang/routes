<?php

declare(strict_types=1);

namespace LaravelLang\Routes\Middlewares;

use Illuminate\Http\Request;

class LocalizationBySession extends Middleware
{
    protected function detect(Request $request): bool|float|int|string|null
    {
        if (! $request->hasSession()) {
            return null;
        }

        return $request->getSession()->get(
            $this->routeConfig->name->session
        );
    }
}
