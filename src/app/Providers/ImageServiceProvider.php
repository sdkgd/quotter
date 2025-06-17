<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ImageServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        if($this->app->environment('production')){
            $this->app->bind(
                \App\Services\ImageService::class, 
                \App\Services\S3ImageServiceImpl::class
            );
        }else{
            $this->app->bind(
                \App\Services\ImageService::class, 
                \App\Services\LocalImageServiceImpl::class
            );
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
