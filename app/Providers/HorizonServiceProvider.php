<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Laravel\Horizon\Horizon;
use Laravel\Horizon\HorizonApplicationServiceProvider;

class HorizonServiceProvider extends HorizonApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        parent::boot();

        // Перезаписуємо middleware group без Sentinel
        Route::middlewareGroup('horizon', config('horizon.middleware', ['web']));
    }

    /**
     * Register the Horizon gate.
     *
     * This gate determines who can access Horizon in non-local environments.
     */
    protected function gate(): void
    {
        Gate::define('viewHorizon', function ($user = null) {
            return true;
            /** Можна додати окремі емейли для доступу
             *   return in_array(optional($user)->email, [
             *   //
             *   ]);
             */
        });
    }
}

