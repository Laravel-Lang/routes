<?php

declare(strict_types=1);

use LaravelLang\Config\Facades\Config;

use function Pest\Laravel\getJson as baseGetJson;

/**
 * Helper to make GET JSON request with blank Accept-Language header by default.
 */
function getJson(string $uri, array $headers = [])
{
    return baseGetJson($uri, array_merge([
        Config::shared()->routes->names->header => ''
    ], $headers));
}
