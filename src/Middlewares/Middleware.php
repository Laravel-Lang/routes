<?php

declare(strict_types=1);

namespace LaravelLang\Routes\Middlewares;

use Closure;
use Illuminate\Http\Request;
use LaravelLang\Routes\Concerns\KeyNames;
use LaravelLang\Routes\Services\Resolver;

use function app;

abstract class Middleware
{
    use KeyNames;

    abstract protected function detect(Request $request): bool|float|int|string|null;

    public function __invoke(Request $request, Closure $next)
    {
        if ($locale = $this->getLocale($request)) {
            $this->setLocale($locale);
        }

        return $next($request);
    }

    protected function getLocale(Request $request): string
    {
        return Resolver::locale(
            $this->detect($request)
        );
    }

    protected function setLocale(string $locale): void
    {
        app()->setLocale($locale);
    }

    protected function trim(?string $locale): ?string
    {
        return is_string($locale) ? trim($locale) : null;
    }
}
