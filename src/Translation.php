<?php

namespace ByPikod\Localization;

use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    protected $guarded = [];
    protected $fillable = ['name', 'value', 'locale'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('translation.database.translations_table');
    }
}
