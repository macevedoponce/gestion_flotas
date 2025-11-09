<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

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
        //

        // 🚀 Super Admin tiene acceso total a todo
        Gate::before(function (User $user, $ability) {
            if ($user->hasRole('Super Admin')) {
                return true;
            }
        });
    }
}
