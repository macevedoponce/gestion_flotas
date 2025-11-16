<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use Illuminate\Support\Facades\URL;

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
        $appUrl = env('APP_URL');

        if (str_contains($appUrl, '.app.github.dev')) {
            // Forzar URLs absolutas correctas
            URL::forceRootUrl($appUrl);
            URL::forceScheme('https');
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
            URL::forceScheme('https');
        }

        // 🚀 Super Admin tiene acceso total a todo
        Gate::before(function (User $user, $ability) {
            if ($user->hasRole('Super Admin')) {
                return true;
            }
        });
    }
}
