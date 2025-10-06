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
        // $locales = new BrowserLocale($request->header($this->names()->header))
        //     ->filter(new CombinedFilter);

        $locales = app(BrowserLocale::class)->filter(new CombinedFilter);

        foreach ($locales as $locale) {
            if (Locales::isInstalled($locale)) {
                return $locale;
            }
        }

        return null;
    }
}
