<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Passport::tokensCan(
            [
                config('app.roles.admin') => 'Puede realizar todas las operaciones',
                config('app.roles.operator') => 'Gestionar proyectos, tareas, finalizar proyectos y tareas, cambiar su password.'
            ]
        );
        Passport::routes();
        Passport::personalAccessTokensExpireIn(now()->addMonths(12));
    }
}
