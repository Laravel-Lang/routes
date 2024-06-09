<?php

declare(strict_types=1);

namespace LaravelLang\Routes\Middlewares;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use LaravelLang\Locales\Facades\Locales;
use LaravelLang\Routes\Concerns\KeyNames;

use function array_merge;
use function response;

class ParameterRedirectLocale extends Middleware
{
    use KeyNames;

    public function __invoke(Request $request, Closure $next)
    {
        if ($this->doesntHaveParameter($request) && ($name = $this->routeName($request))) {
            return $this->redirect($name, $this->parameters($request));
        }

        return parent::__invoke($request, $next);
    }

    protected function detect(Request $request): ?string
    {
        return $request->route()?->parameter($this->names()->parameter);
    }

    protected function doesntHaveParameter(Request $request): ?bool
    {
        return ! $request->route()?->hasParameter($this->names()->parameter)
            || ! $this->trim($request->route()->parameter($this->names()->parameter));
    }

    protected function parameters(Request $request): array
    {
        return array_merge($request->route()?->parameters() ?? [], [
            $this->names()->parameter => $this->defaultLocale(),
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
}
