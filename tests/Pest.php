<?php

uses(Tests\TestCase::class)->in('Feature', 'Unit');

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});
