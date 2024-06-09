<?php

declare(strict_types=1);

namespace LaravelLang\Routes\Concerns;

use LaravelLang\Config\Data\RouteNameData;
use LaravelLang\Config\Facades\Config;

trait KeyNames
{
    public function names(): RouteNameData
    {
        return Config::shared()->routes->names;
    }
}
