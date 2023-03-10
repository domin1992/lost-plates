<?php

namespace App\Providers;

use App\Libraries\GoogleGeocoding;
use Illuminate\Support\ServiceProvider;

class GoogleServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->singletonGoogleGeocoding();
    }

    public function singletonGoogleGeocoding(): void
    {
        $this->app->singleton(GoogleGeocoding::class);

        $this->app->when(GoogleGeocoding::class)
            ->needs('$googleCloudApiKey')
            ->give(config('services.google.cloud_api_key'));
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return [
            GoogleGeocoding::class,
        ];
    }
}
