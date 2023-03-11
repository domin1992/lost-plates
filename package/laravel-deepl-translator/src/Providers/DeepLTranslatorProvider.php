<?php

namespace Zencoreitservices\Translator\Providers;

use Illuminate\Support\ServiceProvider;
use Zencoreitservices\Translator\Console\Commands\Translate;
use Zencoreitservices\Translator\Engines\DeepLEngine;

class DeepLTranslatorProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     */
    public function boot(): void
    {
        // Publish config
        $this->publishes([
            __DIR__ . '/../../config/translator.php' => config_path('translator.php'),
        ], 'translator-config');

        // Console commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                Translate::class,
            ]);
        }

        // Views
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'translator');

        $this->singletonDeepLEngine();
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Config merge
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/translator.php',
            'translator'
        );
    }

    private function singletonDeepLEngine(): void
    {
        $this->app->singleton(DeepLEngine::class);

        $this->app->when(DeepLEngine::class)
            ->needs('$apiKey')
            ->give(config('translator.deeplApiKey'));
    }
}
