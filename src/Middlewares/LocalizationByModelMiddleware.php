<?php

declare(strict_types=1);

namespace LaravelLang\Routes\Middlewares;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use LaravelLang\LocaleList\Locale;

class LocalizationByModelMiddleware extends Middleware
{
    protected ?string $guard = null;

    public function __invoke(Request $request, Closure $next, ?string $guard = null)
    {
        if ($guard === null) {
            return parent::__invoke($request, $next);
        }

        $middleware = clone $this;
        $middleware->guard = $guard;

        return $middleware($request, $next);
    }

    protected function detect(Request $request): int|string|null
    {
        if ($this->has($user = $this->user($request))) {
            $locale = $user->getAttribute($this->attribute());

            return $locale instanceof Locale ? $locale->value : $locale;
        }

        return null;
    }

    protected function has(?Model $user): bool
    {
        return $user && $this->hasAttribute($user);
    }

    protected function user(Request $request): ?Model
    {
        return $request->user($this->guard);
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
