<?php

declare(strict_types=1);

namespace LaravelLang\Routes\Middlewares;

use Illuminate\Http\Request;
use LaravelLang\Config\Facades\Config;

class LocalizationByParameter extends Middleware
{
    protected function detect(Request $request): bool|float|int|string|null
    {
        if ($this->present($request)) {
            return $request->route()->parameter($this->names()->parameter);
        }

        return null;
    }

    protected function present(Request $request): bool
    {
        return in_array(Config::shared()->routes->names->parameter, $request->route()->parameterNames(), true);
    }
}
