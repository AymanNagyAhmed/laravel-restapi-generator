<?php

namespace lararest\RestApiGenerator;

use lararest\RestApiGenerator\Commands\GenerateApi;
use Illuminate\Support\ServiceProvider;

class RestApiGeneratorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'restapi-generator');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'restapi-generator');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('restapi-generator.php'),
            ], 'config');

            // Publishing the views.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/restapi-generator'),
            ], 'views');*/

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/restapi-generator'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/restapi-generator'),
            ], 'lang');*/

            // Registering package commands.
            $this->commands([
                GenerateApi::class,
            ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'restapi-generator');

        // Register the main class to use with the facade
        $this->app->singleton('restapi-generator', function (string $model) {
            return new RestApiGenerator($model);
        });

        $this->app['router']->middleware('ApiHeaderInject', 'lararest\RestApiGenerator\Middleware\ApiHeaderInject');

        $this->app['router']->aliasMiddleware('ApiHeaderInject', \lararest\RestApiGenerator\Middleware\ApiHeaderInject::class);
        $this->app['router']->pushMiddlewareToGroup('api', \lararest\RestApiGenerator\Middleware\ApiHeaderInject::class);
    }
}
