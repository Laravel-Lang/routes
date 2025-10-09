<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Event;

uses(Tests\TestCase::class, DatabaseTransactions::class)
    ->in('Feature', 'Unit')
    ->beforeEach(function () {
        Event::fake();

        $this->withHeader('Accept-Language', 'zxc');
    });
