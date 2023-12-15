<?php

namespace Database\Factories;

use ByPikod\Localization\Translation;
use Illuminate\Database\Eloquent\Factories\Factory;

/** */
class TranslationFactory extends Factory
{
    protected $model = Translation::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'locale' => fake()->languageCode(),
            'key' => fake()->unique()->word(),
            'value' => fake()->sentence(),
        ];
    }
}
