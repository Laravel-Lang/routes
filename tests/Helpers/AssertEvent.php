<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Event;
use LaravelLang\Routes\Events\LocaleHasBeenSetEvent;

function assertEventDispatched(): void
{
    Event::assertDispatched(LocaleHasBeenSetEvent::class);
}

function assertEventNotDispatched(): void
{
    Event::assertNotDispatched(LocaleHasBeenSetEvent::class);
}
