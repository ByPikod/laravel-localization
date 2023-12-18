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
        $this->app->singleton("localizer", function ($app) {
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
     * @return void
     */
    protected function registerBladeDirectives(): void
    {
        /**
         * Blade directive @t
         * This directive is used to print translations in blade templates.
         * It calls addQueue() before printing getCachedTranslation().
         * addQueue adds translation to the queue of translations to be queried from database.
         * and after page is entirely rendered all translations are queried from database
         * to be cached in localizer singleton using fetchTranslations(). fetchTranslations() called
         * automatically after the response is prepared by Laravel. But in case you are not using
         * Laravel Router you should call fetchTranslations() manually.
         * Then the PHP code in the rendered page is executed and getCachedTranslation() is called for
         * each translation.
         */
        Blade::directive('t', function ($expression) {
            /**
             * Simulate PHP function parameters for blade directive @t
             * This function is used to parse the arguments of blade directive @t
             */
            $simulateParametersForT = function (string $key, string $locale = null) {
                $localizer = app("localizer");
                $localizer->addQueue($key, $locale);
                if ($locale === null) {
                    return "<?php echo getCachedTranslation('" . $key . "') ?>";
                }
                return "<?php echo getCachedTranslation('" . $key . "', '" . $locale . "') ?>";
            };
            try {
                $result = eval("return \$simulateParametersForT($expression);");
                return $result;
            } catch (\Throwable $th) {
                throw new \Exception("Failed to execute blade directive @t: " . $th->__toString());
            }
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
