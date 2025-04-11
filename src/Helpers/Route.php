<?php

declare(strict_types=1);

namespace LaravelLang\Routes\Helpers;

use LaravelLang\Config\Facades\Config;
use LaravelLang\Locales\Facades\Locales;

class Route
{
    public static function prefix(): string
    {
        return Config::shared()->routes->namePrefix;
    }

    public static function redirect(): bool
    {
        return Config::shared()->routes->redirect;
    }

    public static function hide(): bool
    {
        return Config::shared()->routes->hide;
    }

    public static function hidingFallback(): bool
    {
        return self::hide() && Locales::raw()->getFallback() === Locales::raw()->getCurrent();
    }
}
