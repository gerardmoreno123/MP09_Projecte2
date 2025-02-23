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
        Gate::before(function (User $user) {
            return $user->hasRole('super-admin') ? true : null;
        });

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
