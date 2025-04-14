<?php

declare(strict_types=1);

use LaravelLang\Config\Facades\Config;
use Tests\Constants\LocaleValue;
use LaravelLang\Config\Enums\Name;

test('route groups', function () {
    $name     = Config::shared()->routes->namePrefix;
    $locale   = LocaleValue::LocaleMain;
    $fallback = LocaleValue::LocaleAliasParent;

    expect(localizedRoute($name . 'via.group.facade', ['foo' => 'bar']))
        ->toEndWith("localhost/$locale/group/facade/bar");

    expect(localizedRoute($name . 'via.group.macro', ['foo' => 'bar', 'locale' => $fallback]))
        ->toEndWith("localhost/$fallback/group/macro/bar");

    expect(localizedRoute('via.group.facade', ['foo' => 'bar']))
        ->toEndWith("localhost/$locale/group/facade/bar");

    expect(localizedRoute('via.group.macro', ['foo' => 'bar', 'locale' => $fallback]))
        ->toEndWith("localhost/$fallback/group/macro/bar");
});

test('routes without groups', function () {
    $locale = LocaleValue::LocaleMain;

    expect(localizedRoute('via.model.default', ['foo' => 'bar']))
        ->toEndWith('localhost/model/default/bar?locale=' . $locale);
});

test('routes hide fallback', function (bool $set) {
    $locale   = LocaleValue::LocaleMain;
    $fallback = LocaleValue::LocaleMain;
    $name     = Config::shared()->routes->names->parameter;

    config()->set(Name::Shared() . '.routes.hide_default', true);
    config()->set('app.fallback_locale', $fallback);

    $params = $set
        ? ['foo' => 'bar', $name => $locale]
        : ['foo' => 'bar'];

    expect(localizedRoute('via.parameter', $params))
        ->toEndWith("localhost/path/bar");
})->with([true, false]);

test('routes hide manual', function () {
    $locale   = LocaleValue::LocaleMain;
    $fallback = LocaleValue::LocaleAliasParent;
    $name     = Config::shared()->routes->names->parameter;

    config()->set(Name::Shared() . '.routes.hide_default', true);
    config()->set('app.fallback_locale', $fallback);

    expect(localizedRoute('via.group.macro', ['foo' => 'bar', $name => $locale]))
        ->toEndWith("localhost/path/bar/$locale");

    expect(localizedRoute('via.parameter', ['foo' => 'bar', $name => $fallback]))
        ->toEndWith("localhost/path/bar");
});

test('routes does not hide', function () {
    $locale   = LocaleValue::LocaleMain;
    $fallback = LocaleValue::LocaleAliasParent;

    config()->set(Name::Shared() . '.routes.hide_default', true);
    config()->set('app.fallback_locale', $fallback);

    expect(localizedRoute('via.parameter', ['foo' => 'bar']))
        ->toEndWith("localhost/path/bar/$locale");
});
