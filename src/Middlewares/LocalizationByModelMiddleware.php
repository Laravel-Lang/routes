<?php

declare(strict_types=1);

namespace LaravelLang\Routes\Middlewares;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use LaravelLang\LocaleList\Locale;
use TypeError;

use function is_int;
use function is_string;

class LocalizationByModelMiddleware extends Middleware
{
    protected ?string $guard = null;

    /**
     * @template TResponse
     *
     * @param  Closure(Request): TResponse  $next
     * @return TResponse
     */
    public function __invoke(Request $request, Closure $next, ?string $guard = null)
    {
        if ($guard === null) {
            return parent::__invoke($request, $next);
        }

        $middleware        = clone $this;
        $middleware->guard = $guard;

        return $middleware($request, $next);
    }

    protected function detect(Request $request): int|string|null
    {
        if ($this->has($user = $this->user($request))) {
            $locale = $user->getAttribute($this->attribute());

            return match (true) {
                $locale instanceof Locale                             => $locale->value,
                $locale === null, is_int($locale), is_string($locale) => $locale,
                default                                               => throw new TypeError('The model locale must be a Locale, an integer, a string, or null'),
            };
        }

        return null;
    }

    /** @phpstan-assert-if-true =Model $user */
    protected function has(?Model $user): bool
    {
        return $user && $this->hasAttribute($user);
    }

    protected function user(Request $request): ?Model
    {
        $user = $request->user($this->guard);

        if ($user !== null && ! $user instanceof Model) {
            throw new TypeError('The authenticated user must be an Eloquent model');
        }

        return $user;
    }

    protected function attribute(): string
    {
        return $this->routeConfig->name->column;
    }

    protected function hasAttribute(Model $user): bool
    {
        return $user->hasAttribute($this->attribute());
    }
}
