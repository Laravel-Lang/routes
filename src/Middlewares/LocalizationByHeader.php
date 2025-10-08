<?php

declare(strict_types=1);

namespace LaravelLang\Routes\Middlewares;

use CodeZero\BrowserLocale\BrowserLocale;
use CodeZero\BrowserLocale\Filters\CombinedFilter;
use Illuminate\Http\Request;
use LaravelLang\Locales\Facades\Locales;

class LocalizationByHeader extends Middleware
{
    protected function detect(Request $request): bool|float|int|string|null
    {
        $locales = $this->getLocales($request);

        foreach ($locales as $locale) {
            if (Locales::isInstalled($locale)) {
                return $locale;
            }
        }

        return null;
    }

    private function getLocales(Request $request): array
    {
        return (new BrowserLocale($this->header($request)))
            ->filter(new CombinedFilter);
    }

    private function header(Request $request): ?string
    {
        return $request->header($this->attribute());
    }

    private function attribute(): string
    {
        return $this->names()->header;
    }
}
