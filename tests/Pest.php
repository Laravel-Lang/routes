<?php

use Illuminate\Support\Facades\Event;

uses(Tests\TestCase::class)
    ->beforeEach(fn () => Event::fake())
    ->in('Feature', 'Unit');
