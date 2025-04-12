<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Middleware\RoleMiddleware;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->define_gates();
        $this->register_policy();

        Route::aliasMiddleware('role', RoleMiddleware::class);
    }

    private function define_gates(): void
    {
        // Super admin gate
        Gate::before(function (User $user) {
            return $user->hasRole('super-admin') ? true : null;
        });

        // Gates for video CRUD operations
        Gate::define('view-videos', function (User $user) {
            return $user->hasPermissionTo('view-videos');
        });

        Gate::define('create-videos', function (User $user) {
            return $user->hasPermissionTo('create-videos');
        });

        Gate::define('edit-videos', function (User $user) {
            return $user->hasPermissionTo('edit-videos');
        });

        Gate::define('delete-videos', function (User $user) {
            return $user->hasPermissionTo('delete-videos');
        });

        // Gates for user CRUD operations
        Gate::define('view-users', function (User $user) {
            return $user->hasPermissionTo('view-users');
        });

        Gate::define('create-users', function (User $user) {
            return $user->hasPermissionTo('create-users');
        });

        Gate::define('edit-users', function (User $user) {
            return $user->hasPermissionTo('edit-users');
        });

        Gate::define('delete-users', function (User $user) {
            return $user->hasPermissionTo('delete-users');
        });

        // Gates for series CRUD operations
        Gate::define('view-series', function (User $user) {
            return $user->hasPermissionTo('view-series');
        });

        Gate::define('create-series', function (User $user) {
            return $user->hasPermissionTo('create-series');
        });

        Gate::define('edit-series', function (User $user) {
            return $user->hasPermissionTo('edit-series');
        });

        Gate::define('delete-series', function (User $user) {
            return $user->hasPermissionTo('delete-series');
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
