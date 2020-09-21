<?php

namespace App\Providers;

use App\Projectuser;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Role;

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
      #TODO: Get role by rid from user
        $this->registerPolicies();

        Gate::define('isAdmin', function($user)
        {
          return Role::where('id', $user->rid)->pluck('name')->first() == 'admin';
        });

        Gate::define('isManager', function($user)
        {
          return Role::where('id', $user->rid)->pluck('name')->first() == 'manager';
        });

        Gate::define('isManagerOrAdmin', function($user)
        {
          $this_role = Role::where('id', $user->rid)->pluck('name')->first();
          return $this_role == 'manager' || $this_role == 'admin';
        });

        Gate::define('isBasic', function($user)
        {
          return Role::where('id', $user->rid)->pluck('name')->first() == 'basic';
        });
    }
}
