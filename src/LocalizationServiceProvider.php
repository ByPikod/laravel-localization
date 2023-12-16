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
        $this->registerBindings();
        $this->registerBladeDirectives();
    }

    /**
     * Register package bindings in the container
     * @return void
     */
    protected function registerBindings(): void
    {
        $this->app->singleton(Localizer::class, function ($app) {
            $translation = new Translation();
            return new Localizer($app, $translation);
        });
    }

    /**
     * Merge the package configuration with the application configuration
     * @return array
     */
    protected function mergeConfiguration(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/localization.php', 'localization');
    }

    /**
     * Register blade directives
     */
    protected function registerBladeDirectives(): void
    {
        Blade::directive('t', function ($key) {
            $localizer = app(Localizer::class);
            $localizer->fetchTranslation($key, app()->getLocale());
            return "<?php echo getCachedTranslation('$key') ?>";
        });
    }

    /**
     * Load migrations that creates database tables
     * @return array
     */
    protected function loadMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }
}
