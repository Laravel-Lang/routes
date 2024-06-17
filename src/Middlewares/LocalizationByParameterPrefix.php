<?php

declare(strict_types=1);

namespace LaravelLang\Routes\Middlewares;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use LaravelLang\Routes\Helpers\Route;

class LocalizationByParameterPrefix extends LocalizationByParameterWithRedirect
{
    protected function parameters(Request $request): array
    {
        return Arr::except($request->route()?->parameters() ?? [], [
            $this->names()->parameter,
        ]);
    }

    protected function routeName(Request $request): ?string
    {
        if ($name = $request->route()?->getName()) {
            return Str::after($name, $this->routePrefix());
        }

        return null;
    }

    protected function routePrefix(): string
    {
        return Route::prefix();
    }
}
