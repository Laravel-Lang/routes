<?php

declare(strict_types=1);

namespace LaravelLang\Routes\Middlewares;

use Illuminate\Http\Request;

class SessionLocale extends Middleware
{
    protected function detect(Request $request): ?string
    {
        return $request->getSession()->get($this->names()->session);
    }
}
