<?php

namespace ByPikod\Localization\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void fetchTranslations(): void
 * @method static void addQueue(string $name, string $locale): void
 * @method static string getCachedTranslation(string $name, string $locale): string
 * @method static void updateTranslation(string $name, string $value, string $locale): void
 * @method static void updateTranslations(array $translations, string $locale): void
 */

class Localizer extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'localizer';
    }
}
