<?php

declare(strict_types=1);

namespace LaravelLang\Routes\Events;

use Illuminate\Foundation\Events\Dispatchable;
use LaravelLang\NativeLocaleNames\LocaleData;

final readonly class LocaleHasBeenSetEvent
{
    use Dispatchable;

    public function __construct(
        public LocaleData $locale
    ) {}
}
