<?php

namespace ByPikod\Localization;

use Illuminate\Foundation\Http\Events\RequestHandled;

/**
 * Singleton class for localization
 */
class Localizer
{
    protected $translation;
    protected $app;
    protected $events;
    protected $fallback_locale;

    // Localization store
    protected array $queue = [];
    protected array $cache = [];

    public function __construct($app, Translation $translation)
    {
        $this->app = $app;
        $this->fallback_locale = config('app.fallback_locale');
        $app["events"]->listen(RequestHandled::class, function () {
            $this->fetchTranslations();
        });

        $this->translation = $translation;
    }

    /**
     * This function queries the database for queued translations
     * and stores them in localizer singleton.
     * It is called just after the response is prepared by laravel.
     * If you are not using Laravel Router you should call this function manually.
     * @param string $locale The locale of the translation
     * @return void
     */
    public function fetchTranslations(): void
    {
        // get translations from database locale by locale
        $translations = [];
        foreach ($this->queue as $locale => $names) {
            $translations[$locale] = $this->translation->getTranslations($names, $locale);
        }
        // find missing translations
        $missingTranslations = [];
        foreach ($this->queue as $locale => $names) {
            foreach ($names as $name) {
                if (!isset($translations[$locale][$name])) {
                    $missingTranslations[] = $name;
                }
            }
        }
        // get missing translations from fallback locale
        $translations[$this->fallback_locale] = $translations[$this->fallback_locale] ?? [];
        if (count($missingTranslations) > 0) {
            $fallbackTranslations = $this->translation->getTranslations($missingTranslations, $this->fallback_locale);
            foreach ($missingTranslations as $name) {
                $translations[$this->fallback_locale][$name] = $fallbackTranslations[$name] ?? $name;
            }
        }
        // store translations in localizer singleton
        $this->cache = $translations;
    }

    /**
     * This function adds translation to the queue of
     * translations to be queried from database.
     * It is called when blade directive @t is used.
     * @param string $name The key for the translation
     * @param string $locale The locale of the translation
     * @return void
     */
    public function addQueue($name, $locale): void
    {
        // create locale array if not exists
        $this->queue[$locale] = $this->queue[$locale] ?? [];
        // add translation to queue
        $this->queue[$locale][] = $name;
    }

    /**
     * This function retrieves cached translation from localizer singleton.
     * It is called from a helper function named getCachedTranslation()
     * which is printed to the document by the blade directive @t.
     * @see src/helpers.php
     * @see ByPikod\Localization\Localizer
     * @param string $name The key for the translation
     * @param string $locale The locale of the translation
     * @return string The translation
     */
    public function translate($name, $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();
        echo "name: $name, locale: $locale\n";
        // TODO: Maybe this checks should be done somewhere else
        // or a better design might be considered
        if (!isset($this->cache[$locale])) {
            $this->cache[$locale] = [];
        }
        if (!isset($this->cache[$this->fallback_locale])) {
            $this->cache[$this->fallback_locale] = [];
        }
        return $this->cache[$locale][$name] ?? $this->cache[$this->fallback_locale][$name] ?? $name;
    }

    /**
     * This function updates translation in database.
     * @param string $name The key for the translation
     * @param string $value The value for the translation
     * @param string $locale The locale of the translation (default is current locale)
     * @return void
     */
    public function updateTranslation($name, $value, $locale = null): void
    {
        $this->translation->updateTranslation($name, $value, $locale);
    }

    /**
     * This function updates translations in bulk.
     * @param array $translations Array of translations to be updated
     * @param string $locale Locale to update translations for (default is current locale)
     * @return void
     */
    public function updateTranslations(array $translations, $locale = null): void
    {
        $converted = [];
        $locale = $locale ?? app()->getLocale();
        foreach ($translations as $name => $value) {
            if (is_array($value)) {
                foreach ($value as $locale => $value) {
                    $converted[] = ['name' => $name, 'value' => $value, 'locale' => $locale];
                }
            } else {
                $converted[] = ['name' => $name, 'value' => $value, 'locale' => $locale];
            }
        }
        $this->translation->bulkUpdate($converted);
    }
}
