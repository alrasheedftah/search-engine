<?php

namespace ana\searchEngine;

use Illuminate\Support\ServiceProvider;

class SearchEngineProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../config/searchengine.php' => config_path('searchengine.php'),
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('searchengine', function () {
            return new SearchEngine(config('searchengine.google_search_engine_id'), config('searchengine.google_search_api_key'));
        });
    }
}