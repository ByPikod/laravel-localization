<?php

namespace ByPikod\Localization;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class LocalizationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     * @return void
     */
    public function boot(): void
    {
        $this->loadMigrations(); // Load migrations
    }

    /**
     * Register package bindings in the container
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfiguration();
        $this->registerBladeDirectives();
    }

    /**
     * Merge the package configuration with the application configuration
     * @return array
     */
    public function mergeConfiguration(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/localization.php', 'localization');
    }

    /**
     * Register blade directives
     */
    public function registerBladeDirectives(): void
    {
        Blade::directive('t', function ($expression) {
            return "<?php \$translations[] = '$expression' ?>";
        });
    }

    /**
     * Load migrations that creates database tables
     * @return array
     */
    public function loadMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }
}
