<?php

if (!function_exists('getCachedTranslation')) {
    /**
     * This function retrieves cached translation from localizer singleton.
     * It is printed to the document by the blade directive @t.
     * Blade directive @t calls fetchTranslation() before printing getCachedTranslation().
     * fetchTranslation adds translation to the queue of translations to be queried from database.
     * and after page is entirely rendered all translations are queried from database
     * to be cached in localizer singleton.
     * Then the PHP code in the rendered page is executed and getCachedTranslation() is called for
     * each translation.
     * @see ByPikod\Localization\Localizer
     * @param string $name
     * @param string $locale
     * @return string
     */
    function getCachedTranslation($name, $locale = null)
    {
        return app(ByPikod\Localization\Localizer::class)->translate($name, $locale);
    }
}
