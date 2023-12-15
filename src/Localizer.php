<?php

namespace ByPikod\Localization;

class Localizer
{
    protected $translation;
    protected $app;

    public function __construct(Translation $translation, $app)
    {
        $this->translation = $translation;
        $this->app = $app;
    }
}
