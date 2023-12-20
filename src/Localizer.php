<?php

namespace ByPikod\Localization;

/**
 * Singleton class for localization
 */
class Localizer
{
    protected $translation; // Translation model
    protected $fallback_locale; // Language to retrieve if requested key language pair is not found
    protected $cached = false; // Whether translations are cached or not

    // Localization store
    protected array $queue = []; // Queue of translations to be queried from database
    protected array $cache = []; // Cached translations

    public function __construct(Translation $translation)
    {
        $this->fallback_locale = config('app.fallback_locale');
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
            $fetch = $this->translation->getTranslations($names, $locale);
            foreach ($fetch as $translation) {
                $translations[$locale][$translation->name] = $translation->value;
            }
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
            foreach ($fallbackTranslations as $translation) {
                $translations[$this->fallback_locale][$translation->name] = $translation->value;
            }
        }
        // store translations in localizer singleton
        $this->cache = $translations;
        $this->cached = true;
    }

    /**
     * This function adds translation to the queue of
     * translations to be queried from database.
     * It is called when blade directive @t is used.
     * @param string $name The key for the translation
     * @param string $locale The locale of the translation
     * @return void
     */
    public function addQueue($name, $locale = null): void
    {
        // get current locale if not provided
        $locale = $locale ?? app()->getLocale();
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
        if (!$this->cached) {
            $this->fetchTranslations();
        }
        $locale = $locale ?? app()->getLocale();
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

    public function isCached(): bool
    {
        return $this->cached;
    }

    // The code below is for debugging purposes only

    /*
    public array $functionLog = [];

    public function printFunctionLog()
    {
        $i = 1;
        foreach ($this->functionLog as $fn) {
            echo "#$i: $fn\n";
            $i++;
        }
    }

    public function addFunctionLog(string $fnName, string ...$args)
    {
        $this->functionLog[] = $fnName . "(" . implode(", ", $args) . ")";
    }
    */
}
