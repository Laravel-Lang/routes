<?php

declare(strict_types=1);

use LaravelLang\LocaleList\Locale;
use Tests\Constants\LocaleValue;

dataset('main-locales', [
    LocaleValue::LocaleMain,
]);

dataset('aliased-locales', [
    LocaleValue::LocaleAliasParent,
    LocaleValue::LocaleAlias,
]);

dataset('empty-locales', [
    '',
    null,
    0,
]);

dataset('uninstalled-locales', [
    Locale::Assamese->value,
    Locale::NorwegianBokmal->value,
    Locale::Bulgarian->value,
]);

dataset('unknown-locales', [
    'foo',
    'bar',
    'qwerty',
    123,
]);
