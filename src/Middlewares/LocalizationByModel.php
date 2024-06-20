<?php

declare(strict_types=1);

namespace LaravelLang\Routes\Middlewares;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class LocalizationByModel extends Middleware
{
    public function __construct(
        protected ?string $guard = null,
    ) {}

    protected function detect(Request $request): bool|float|int|string|null
    {
        if ($this->user($request)?->hasAttribute($this->attribute())) {
            return $this->user($request)->getAttribute($this->attribute());
        }

        return null;
    }

    protected function user(Request $request): ?Model
    {
        return $request->user($this->guard);
    }

    protected function attribute(): string
    {
        return $this->names()->column;
    }
}
