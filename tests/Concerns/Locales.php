<?php

declare(strict_types=1);

namespace Tests\Concerns;

use DragonCode\Support\Facades\Filesystem\File;
use Tests\Constants\LocaleValue;

use function json_encode;
use function lang_path;

trait Locales
{
    public function setUpLocales(): void
    {
        $this->storeLocale(LocaleValue::LocaleMain, LocaleValue::TranslationFrench);
        $this->storeLocale(LocaleValue::LocaleAlias, LocaleValue::TranslationGerman);
    }

    protected function storeLocale(string $locale, string $value): void
    {
        if (! File::exists($path = $this->localePath($locale))) {
            File::store($path, json_encode([LocaleValue::TranslationKey => $value]));
        }
    }

    protected function localePath(string $locale): string
    {
        return lang_path($locale . '.json');
    }
}
