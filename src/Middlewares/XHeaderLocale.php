<?php

declare(strict_types=1);

namespace LaravelLang\Routes\Middlewares;

use Illuminate\Http\Request;

class XHeaderLocale extends Middleware
{
    protected function detect(Request $request): ?string
    {
        return $request->header($this->names()->header);
    }
}
