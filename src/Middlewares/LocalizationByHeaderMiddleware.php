<?php

declare(strict_types=1);

namespace LaravelLang\Routes\Middlewares;

use Illuminate\Http\Request;
use Locale;

class LocalizationByHeaderMiddleware extends Middleware
{
    protected function detect(Request $request): int|string|null
    {
        if (! $header = $this->header($request)) {
            return null;
        }

        return $this->parse($header)['language'] ?? null;
    }

    protected function parse(string $header): array
    {
        return Locale::parseLocale($header);
    }

    protected function header(Request $request): ?string
    {
        return $request->header(
            $this->routeConfig->name->header
        );
    }
}
