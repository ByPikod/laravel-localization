<?php

namespace ByPikod\Localization;

use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    protected $guarded = [];
    protected $fillable = ['name', 'value', 'locale'];
    public $timestamps = false;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('translation.database.translations_table');
    }

    /**
     * This function queries the database for queued translations
     * and returns them.
     * @param array $names The keys for the translations
     * @param string $locale The locale of the translations
     * @return array
     */
    public function getTranslations(array $names, string $locale): array
    {
        return $this->whereIn('name', $names)->where('locale', $locale)->get();
    }

    /**
     * Update or create translation
     * @param string $name The key for the translation
     * @param string $value The value for the translation
     * @param string $locale The locale of the translation (default is current locale)
     * @return void
     */
    public function updateTranslation(string $name, string $value, string $locale = null): void
    {
        $locale = $locale ?? app()->getLocale();
        $this->updateOrCreate(
            ['name' => $name, 'locale' => $locale],
            ['value' => $value]
        );
    }

    /**
     * Update or create translations in bulk
     * @param array $translations The translations to be updated or created
     * @return void
     */
    public function bulkUpdate(array $translations): void
    {
        $this->upsert($translations, ['name', 'locale'], ['value']);
    }
}
