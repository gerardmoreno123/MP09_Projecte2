<?php

namespace App\Providers;

use App\Helpers\UserHelpers;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Middleware\RoleMiddleware;

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
        $this->define_gates();
        $this->register_policy();

        Route::aliasMiddleware('role', RoleMiddleware::class);
    }

    private function define_gates(): void
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

    protected array $policies = [
        // De moment vas dir que deixéssim buida aquesta array
    ];

    private function register_policy(): void
    {
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }
    }
}
