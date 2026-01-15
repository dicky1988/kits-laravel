<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::before(function ($user, $ability) {

            if (!$user->active_role_id) {
                return false;
            }

            return $user->activeRole
                ->permissions
                ->pluck('name')
                ->contains($ability);
        });
    }
}
