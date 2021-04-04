<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Services\User\UserInterface;
use App\Services\User\UserService;

            use App\Services\User\RoleInterface;
            use App\Services\User\RoleService;

            use App\Services\Project\ProjectInterface;
            use App\Services\Project\ProjectService;

            use App\Services\Task\TaskInterface;
            use App\Services\Task\TaskService;

            #DummyPathInterface
            #DummyPathService

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            UserInterface::class,
            UserService::class
        );

        $this->app->bind(
            RoleInterface::class,
            RoleService::class
        );

        $this->app->bind(
            ProjectInterface::class,
            ProjectService::class
        );

        $this->app->bind(
            TaskInterface::class,
            TaskService::class
        );

        #DummyBind
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
