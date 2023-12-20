<?php

namespace ByPikod\Localization\Tests;

use ByPikod\Localization\Facades\Localizer;
use ByPikod\Localization\LocalizationServiceProvider;
use ByPikod\Localization\Translation;
use Illuminate\Foundation\Testing\Concerns\InteractsWithViews;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Orchestra\Testbench\TestCase;

class LocalizationTest extends TestCase
{
    use DatabaseMigrations;
    use InteractsWithViews;

    private $translation;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->app->setlocale("en");
        $this->artisan('migrate', [
            '--database' => 'testbench',
            '--realpath' => realpath(__DIR__ . '/../migrations'),
        ]);
        $this->withFactories(__DIR__ . '/../database/factories');
        $this->translation = $this->app[Translation::class];
        $this->withoutExceptionHandling();
    }

    /**
     * Define environment setup.
     */
    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    /**
     * Load package service provider to activate package.
     */
    protected function getPackageProviders($app): array
    {
        return [
            // Service provider that will load the package
            LocalizationServiceProvider::class,
        ];
    }

    /** @test */
    // phpcs:ignore
    public function can_create_translation()
    {
        updateTranslation('key.test', 'value');
        $this->assertDatabaseHas('translations', [
            'name' => 'key.test',
            'value' => 'value',
            'locale' => 'en',
        ]);
    }

    /** @test */
    // phpcs:ignore
    public function can_create_translation_bulk_1() {
        updateTranslations([
            'can_create_translation_bulk_1_1' => 'value',
            'can_create_translation_bulk_1_2' => 'value2',
        ], 'en');
        $this->assertDatabaseHas('translations', [
            'name' => 'can_create_translation_bulk_1_1',
            'value' => 'value',
            'locale' => 'en',
        ]);
        $this->assertDatabaseHas('translations', [
            'name' => 'can_create_translation_bulk_1_2',
            'value' => 'value2',
            'locale' => 'en',
        ]);
    }

    /** @test */
    // phpcs:ignore
    public function can_create_translation_bulk_2() {
        updateTranslations([
            'can_create_translation_bulk_2' => [
                'en' => 'value',
                'tr' => 'değer',
            ],
        ]);
        $this->assertDatabaseHas('translations', [
            'name' => 'can_create_translation_bulk_2',
            'value' => 'value',
            'locale' => 'en',
        ]);
        $this->assertDatabaseHas('translations', [
            'name' => 'can_create_translation_bulk_2',
            'value' => 'değer',
            'locale' => 'tr',
        ]);
    }

    /** @test */
    // phpcs:ignore
    public function can_update_translation()
    {
        updateTranslation('can_update_translation', 'value');
        updateTranslation('can_update_translation', 'value2');
        $this->assertDatabaseHas('translations', [
            'name' => 'can_update_translation',
            'value' => 'value2',
            'locale' => 'en',
        ]);
    }

    /** @test */
    // phpcs:ignore
    public function can_update_translation_bulk_1()
    {
        updateTranslations([
            'can_update_translation_bulk_1_1' => 'value',
            'can_update_translation_bulk_1_2' => 'value2',
        ], 'en');
        updateTranslations([
            'can_update_translation_bulk_1_1' => 'value3',
            'can_update_translation_bulk_1_2' => 'value4',
        ], 'en');
        $this->assertDatabaseHas('translations', [
            'name' => 'can_update_translation_bulk_1_1',
            'value' => 'value3',
            'locale' => 'en',
        ]);
        $this->assertDatabaseHas('translations', [
            'name' => 'can_update_translation_bulk_1_2',
            'value' => 'value4',
            'locale' => 'en',
        ]);
    }

    /** @test */
    // phpcs:ignore
    public function can_update_translation_bulk_2()
    {
        updateTranslations([
            'can_update_translation_bulk_2' => [
                'en' => 'value',
                'tr' => 'değer',
            ],
        ]);
        updateTranslations([
            'can_update_translation_bulk_2' => [
                'en' => 'value2',
                'tr' => 'değer2',
            ],
        ]);
        $this->assertDatabaseHas('translations', [
            'name' => 'can_update_translation_bulk_2',
            'value' => 'value2',
            'locale' => 'en',
        ]);
        $this->assertDatabaseHas('translations', [
            'name' => 'can_update_translation_bulk_2',
            'value' => 'değer2',
            'locale' => 'tr',
        ]);
    }

    /** @test */
    // phpcs:ignore
    public function can_blade_directive_translate()
    {
        // Update translation in database
        updateTranslation('can_blade_translate', 'passed');
        // Render blade template
        $rendered = $this->blade('@t("can_blade_translate")'); // Render blade template
        Localizer::fetchTranslations(); // Fetch translations from database
        $rendered->assertSee('passed'); // Assert that rendered template contains the translation
    }

    /** @test */
    // phpcs:ignore
    public function does_autofetch_works() {
        // push new translation to database
        updateTranslation('does_autofetch_works', 'passed');
        // test
        Artisan::call("view:clear");
        Route::get('/autofetch', function () {
            return view()->file(__DIR__ . '/fixtures/autofetch.blade.php');
        });
        $response = $this->get('/autofetch');
        $response->assertStatus(200);
        $response->assertSeeText('passed');
    }

    // TODO: add tests for fallback locale
}
