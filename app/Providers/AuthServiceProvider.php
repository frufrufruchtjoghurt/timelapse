<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Set user roles for role-based authentification
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('isAdmin', function($user)
        {
          return $user->role == 'admin';
        });

        Gate::define('isManager', function($user)
        {
          return $user->role == 'manager';
        });

        Gate::define('isManagerOrAdmin', function($user)
        {
          return $user->role == 'manager' || $user->role == 'admin';
        });

        Gate::define('isBasic', function($user)
        {
          return $user->role == 'basic';
        });
    }
}
