<?php

declare(strict_types=1);

namespace LaravelLang\Routes\Events;

use LaravelLang\NativeLocaleNames\LocaleData;

final readonly class LocaleHasBeenSetEvent
{
    public function __construct(
        public LocaleData $locale
    ) {}
}
