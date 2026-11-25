<?php

declare(strict_types=1);

namespace LaravelLang\Routes\Middlewares;

use Illuminate\Http\Request;
use LaravelLang\Locales\Facades\Locales;
use Locale;
use Symfony\Component\HttpFoundation\AcceptHeader;

class LocalizationByHeaderMiddleware extends Middleware
{
    protected function detect(Request $request): int|string|null
    {
        if (! $header = $this->header($request)) {
            return null;
        }

        $languages = AcceptHeader::fromString($header)->all();
        $installed = Locales::installed(false)->flip();

        foreach ($languages as $language) {
            if ($language->getQuality() <= 0 && ($locale = $this->normalizeLocale($language->getValue())) !== null) {
                $installed->forget($locale);
            }
        }

        foreach ($languages as $language) {
            $value = $language->getValue();

            if ($value === '*' || $language->getQuality() <= 0) {
                continue;
            }

            $locale = $this->normalizeLocale($value);

            if ($locale !== null && $installed->has($locale)) {
                return $locale;
            }

            $base = $this->parse($value)['language'] ?? null;

            if ($base !== null && $installed->has($base)) {
                return $base;
            }
        }

        return null;
    }

    protected function normalizeLocale(string $value): ?string
    {
        $locale = Locales::fromAlias($value);

        return $locale !== $value ? $locale : Locale::canonicalize($value);
    }

    /** @return array<string, string>|null */
    protected function parse(string $header): ?array
    {
        return Locale::parseLocale($header);
    }

    protected function header(Request $request): ?string
    {
        return $request->headers->get(
            $this->routeConfig->name->header
        );
    }
}
