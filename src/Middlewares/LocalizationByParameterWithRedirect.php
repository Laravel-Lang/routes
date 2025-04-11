<?php

declare(strict_types=1);

namespace LaravelLang\Routes\Middlewares;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use LaravelLang\Locales\Facades\Locales;
use LaravelLang\Routes\Concerns\RouteParameters;

use LaravelLang\Routes\Helpers\Route;

use function array_merge;
use function in_array;
use function response;

class LocalizationByParameterWithRedirect extends Middleware
{
    use RouteParameters;

    public function __invoke(Request $request, Closure $next)
    {
        $name = $this->routeName($request);

        if ($this->present($request) && $this->invalidParameter($request) && $name) {
            return $this->redirect($name, $this->parameters($request));
        }

        if (Route::hidingFallback()) {
            return $this->redirect($name, $this->parameters($request, true));
        }

        return parent::__invoke($request, $next);
    }

    protected function detect(Request $request): bool|float|int|string|null
    {
        return $request->route()?->parameter($this->names()->parameter);
    }

    protected function invalidParameter(Request $request): ?bool
    {
        return ! $this->hasParameter($request)
            || ! $this->trim($this->detect($request))
            || ! $this->isInstalled($this->detect($request));
    }

    protected function hasParameter(Request $request): bool
    {
        return (bool) $request->route()?->hasParameter($this->names()->parameter);
    }

    protected function present(Request $request): bool
    {
        return in_array($this->names()->parameter, $request->route()->parameterNames(), true);
    }

    protected function parameters(Request $request, bool $withoutLocale = false): array
    {
        $parameters = $request->route()?->parameters() ?? [];

        if ($withoutLocale) {
            unset($parameters['locale']);
        }

        return array_merge($parameters, [
            $this->names()->parameter => $withoutLocale
                ? $this->defaultLocale()
                : $this->fallbackLocale(),
        ]);
    }

    protected function routeName(Request $request): ?string
    {
        return $request->route()?->getName();
    }

    protected function redirect(string $name, array $parameters): RedirectResponse
    {
        return response()->redirectToRoute($name, $parameters);
    }

    protected function defaultLocale(): string
    {
        return Locales::getDefault()->code;
    }

    protected function fallbackLocale(): string
    {
        return Locales::getFallback()->code;
    }

    protected function isInstalled(bool|float|int|string|null $locale): bool
    {
        return Locales::isInstalled((string) $locale);
    }
}
