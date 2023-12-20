<?php

if (!function_exists('getCachedTranslation')) {
    /**
     * This function retrieves cached translation from localizer singleton.
     * It is printed to the document by the blade directive @t.
     * Blade directive @t calls addQueue() before printing getCachedTranslation().
     * addQueue adds translation to the queue of translations to be queried from database.
     * and after page is entirely rendered all translations are queried from database
     * to be cached in localizer singleton.
     * Then the PHP code in the rendered page is executed and getCachedTranslation() is called for
     * each translation.
     * @see ByPikod\Localization\Localizer
     * @param string $name
     * @param string $locale
     * @return string
     */
    function getCachedTranslation($name, $locale = null): string
    {
        return \ByPikod\Localization\Facades\Localizer::translate($name, $locale);
    }
}

if (!function_exists('updateTranslation')) {
    /**
     * This function updates translation in database.
     * @see ByPikod\Localization\Localizer
     * @param string $name Translation name
     * @param string $value New translation value
     * @param string $locale Locale to update translation for (default is current locale)
     * @return void
     */
    function updateTranslation($name, $value, $locale = null): void
    {
        \ByPikod\Localization\Facades\Localizer::updateTranslation($name, $value, $locale);
    }
}

if (!function_exists('updateTranslations')) {
    /**
     * This function updates translations in bulk.
     * @param array $translations Array of translations to be updated
     * @param string $locale Locale to update translations for (default is current locale)
     * @return void
     * @example ['name' => 'value', 'name2' => 'value2'], 'en'
     * @example ['name' => ['en' => 'value', 'tr' => 'değer'], 'name2' => ['en' => 'value2', 'tr' => 'değer2']]
     */
    function updateTranslations(array $translations, $locale = null): void
    {
        \ByPikod\Localization\Facades\Localizer::updateTranslations($translations, $locale);
    }
}
