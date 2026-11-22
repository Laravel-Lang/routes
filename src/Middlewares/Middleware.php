<?php

declare(strict_types=1);

namespace LaravelLang\Routes\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use LaravelLang\Config\Services\RouteLangConfig;
use LaravelLang\Exceptions\UnknownLocaleException;
use LaravelLang\Locales\Facades\Locales;
use LaravelLang\NativeLocaleNames\LocaleData;
use LaravelLang\Routes\Events\LocaleHasBeenSetEvent;

use function app;
use function session;
use function trim;

abstract class Middleware
{
    abstract protected function detect(Request $request): int|string|null;

    public function __construct(
        protected RouteLangConfig $routeConfig,
    ) {}

    /**
     * @template TResponse
     *
     * @param  Closure(Request): TResponse  $next
     *
     * @return TResponse
     */
    public function __invoke(Request $request, Closure $next)
    {
        try {
            $this->apply($request);
        } catch (UnknownLocaleException) {
            $this->setLocale(
                Locales::fallback()
            );
        }

        return $next($this->forgetParameter($request));
    }

    protected function apply(Request $request): void
    {
        if (! $locale = $this->getLocale($request)) {
            return;
        }

        if ($locale->code === app()->getLocale()) {
            return;
        }

        $this->setLocale($locale->code);
        $this->event($locale);
    }

    protected function getLocale(Request $request): ?LocaleData
    {
        if (! $locale = (string) $this->detect($request)) {
            return null;
        }

        if (! Locales::isInstalled($locale)) {
            return null;
        }

        return Locales::info($locale);
    }

    protected function setLocale(string $locale): void
    {
        Locales::set($locale);

        session()->put($this->routeConfig->name->session, $locale);
    }

    protected function event(LocaleData $locale): void
    {
        event(new LocaleHasBeenSetEvent($locale));
    }

    protected function trim(bool|float|int|string|null $locale): string
    {
        return trim((string) $locale);
    }

    protected function routeLocale(Request $request): int|string|null
    {
        $locale = Arr::get($request->route()?->parameters() ?? [], $this->routeConfig->name->parameter);

        return Locales::normalize($locale);
    }

    protected function forgetParameter(Request $request): Request
    {
        $request->route()?->forgetParameter(
            $this->routeConfig->name->parameter
        );

        return $request;
    }
}
