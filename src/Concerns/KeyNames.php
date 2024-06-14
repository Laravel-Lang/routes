<?php

declare(strict_types=1);

namespace LaravelLang\Routes\Concerns;

use LaravelLang\Config\Data\Shared\RouteNameData;
use LaravelLang\Routes\Helpers\Name;

trait KeyNames
{
    public function names(): RouteNameData
    {
        return Name::all();
    }
}
