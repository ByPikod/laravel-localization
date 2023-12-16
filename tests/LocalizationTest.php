<?php

namespace ByPikod\Localization\Tests;

use ByPikod\Localization\LocalizationBindingsServiceProvider;
use ByPikod\Localization\LocalizationServiceProvider;
use ByPikod\Localization\Translation;
use Illuminate\Foundation\Testing\Concerns\InteractsWithViews;
use Illuminate\Foundation\Testing\DatabaseMigrations;
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
        $this->artisan('migrate', [
            '--database' => 'testbench',
            '--realpath' => realpath(__DIR__ . '/../migrations'),
        ]);
        $this->withFactories(__DIR__ . '/../database/factories');
        $this->translation = $this->app[Translation::class];
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
    public function blade_directives()
    {
        echo "rendered:" . $this->blade('@t("deneme") @t("deneme") @t("deneme")')->__toString();
        $this->assertTrue(true);
    }
}
