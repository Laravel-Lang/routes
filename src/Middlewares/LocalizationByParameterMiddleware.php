<?php

declare(strict_types=1);

namespace LaravelLang\Routes\Middlewares;

use Illuminate\Http\Request;

class LocalizationByParameterMiddleware extends Middleware
{
    protected function detect(Request $request): int|string|null
    {
        if ($this->present($request)) {
            return $this->routeLocale($request);
        }

        return null;
    }

    protected function present(Request $request): bool
    {
        return in_array(
            $this->routeConfig->name->parameter,
            $request->route()?->parameterNames() ?? [],
            true
        );
    }
}
