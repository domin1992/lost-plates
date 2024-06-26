<?php

namespace App\Providers;

use App\View\Components\Front\MetaTagsComponent;
use App\View\Components\Front\GlobalVariablesComponent;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        $this->bootComponents();
    }

    private function bootComponents(): void
    {
        Blade::component('meta-tags', MetaTagsComponent::class);
        Blade::component('global-variables', GlobalVariablesComponent::class);
    }
}
