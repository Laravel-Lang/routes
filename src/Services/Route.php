<?php

declare(strict_types=1);

namespace LaravelLang\Routes\Services;

use Closure;
use Illuminate\Support\Facades\Route as BaseRoute;
use LaravelLang\Routes\Concerns\KeyNames;
use LaravelLang\Routes\Middlewares\LocalizationByCookie;
use LaravelLang\Routes\Middlewares\LocalizationByHeader;
use LaravelLang\Routes\Middlewares\LocalizationByParameter;
use LaravelLang\Routes\Middlewares\LocalizationBySession;

use function sprintf;

class Route
{
    use KeyNames;

    public function group(Closure $callback): void
    {
        BaseRoute::prefix(sprintf('{%s}', $this->names()->parameter))
            ->name($this->names()->parameter . '.')
            ->middleware(LocalizationByParameter::class)
            ->group($callback);

        BaseRoute::middleware([
            LocalizationByCookie::class,
            LocalizationByHeader::class,
            LocalizationBySession::class,
        ])->group($callback);
    }
}
