<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

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
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();


        Gate::define('manage-shifts', function ($user) {
            return ($user->isAdmin() || $user->isAccountant());
        });

        Gate::define('add-man-power', function ($user) {
            return ($user->isAdmin() || $user->isAccountant() || $user->isManager());
        });
        Gate::define('manage-man-power', function ($user) {
            return ($user->isAdmin() || $user->isAccountant());
        });

        //
    }
}
