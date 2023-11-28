<?php

namespace App\Providers;

use App\Services\ImageCompressor;
use App\Services\TechBotService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind('tech_bot.service', TechBotService::class);
        $this->app->bind('image_compressor.service', ImageCompressor::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
    }
}
