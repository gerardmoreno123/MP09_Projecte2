<?php

namespace App\Providers;

use App\Helpers\UserHelpers;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
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
        Gate::before(function (User $user) {
            return $user->hasRole('super-admin') ? true : null;
        });

        Gate::define('view-videos', function (User $user) {
            return $user->hasRole('regular') || $user->hasRole('video-manager') || $user->hasRole('super-admin');
        });

        Gate::define('manage-videos', function (User $user) {
            return $user->hasRole('video-manager') || $user->hasRole('super-admin');
        });
        
        Gate::define('edit-users', function (User $user) {
            return $user->hasRole('super-admin');
        });
    }
}
