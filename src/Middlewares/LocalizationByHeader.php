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
        $locales = $this->getLocales();

        foreach ($locales as $locale) {
            if (Locales::isInstalled($locale)) {
                return $locale;
            }
        }

        return null;
    }

    private function getLocales(): array
    {
        return app(BrowserLocale::class)->filter(new CombinedFilter);
    }
}
