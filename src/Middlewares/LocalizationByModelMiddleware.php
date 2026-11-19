<?php

declare(strict_types=1);

namespace LaravelLang\Routes\Middlewares;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class LocalizationByModelMiddleware extends Middleware
{
    protected function detect(Request $request): int|string|null
    {
        if ($this->has($user = $this->user($request))) {
            return $user->getAttribute($this->attribute());
        }

        return null;
    }

    protected function has(?Model $user): bool
    {
        return $user && $this->hasAttribute($user);
    }

    protected function user(Request $request): ?Model
    {
        return $request->user();
    }

    protected function attribute(): string
    {
        return $this->routeConfig->name->column;
    }

    protected function hasAttribute(Model $user): bool
    {
        if (method_exists($user, 'hasAttribute')) {
            return $user->hasAttribute($this->attribute());
        }

        return array_key_exists($this->attribute(), $user->getAttributes());
    }
}
