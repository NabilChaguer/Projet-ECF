<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // Forcer HTTPS si l'app est en production
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}

