<?php

declare(strict_types=1);

use Tests\Constants\LocaleValue;

dataset('main-locales', [
    LocaleValue::TranslationFrench,
    LocaleValue::LocaleUninstalled,
    LocaleValue::LocaleIncorrect,
]);

dataset('aliased-locales', [
    LocaleValue::LocaleAliasParent,
    LocaleValue::LocaleAlias,
]);

dataset('empty-locales', [
    '',
    ' ',
    null,
    0,
]);
