<?php

namespace ByPikod\Localization;

/**
 * Singleton class for localization
 */
class Localizer
{
    protected $translation;
    protected $translations = [];
    protected $app;

    public function __construct($app, Translation $translation)
    {
        $this->app = $app;
        $this->translation = $translation;
    }

    public function fetchTranslation($name, $locale)
    {
        $this->translations[$locale][$name] = "fetched translation";
    }

    public function translate($name, $locale = null)
    {
        $locale = $locale ?: $this->app->getLocale();
        return $this->translations[$locale][$name] ?? "undefined";
    }
}
