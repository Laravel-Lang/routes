<?php

declare(strict_types=1);

namespace LaravelLang\Routes\Middlewares;

use Closure;
use Illuminate\Http\Request;
use LaravelLang\Config\Services\RouteConfig;
use LaravelLang\Locales\Data\LocaleData;
use LaravelLang\Locales\Facades\Locales;
use LaravelLang\Routes\Events\LocaleHasBeenSetEvent;

use function app;
use function session;
use function trim;

abstract class Middleware
{
    abstract protected function detect(Request $request): bool|float|int|string|null;

    public function __construct(
        protected RouteConfig $routeConfig,
    ) {}

    public function __invoke(Request $request, Closure $next)
    {
        $this->apply($request);

        return $next($this->forgetParameter($request));
    }

    protected function apply(Request $request): void
    {
        if (! $locale = $this->getLocale($request)) {
            return;
        }

        if ($locale->locale->value === app()->getLocale()) {
            return;
        }

        $this->setLocale($locale->locale->value);
        $this->event($locale);
    }

    protected function getLocale(Request $request): ?LocaleData
    {
        if ($locale = $this->trim($this->detect($request))) {
            return Locales::get($locale);
        }

        return null;
    }

    protected function setLocale(string $locale): void
    {
        app()->setLocale($locale);

        if (session()) {
            session()->put($this->routeConfig->name->session, $locale);
        }
    }

    protected function event(LocaleData $locale): void
    {
        LocaleHasBeenSetEvent::dispatch($locale);
    }

    protected function trim(bool|float|int|string|null $locale): string
    {
        return trim((string) $locale);
    }

    protected function forgetParameter(Request $request): Request
    {
        $request->route()->forgetParameter(
          $this->routeConfig->name->parameter
        );

        return $request;
    }
}
