<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            \App\Repositories\Subjects\SubjectInterface::class,
            \App\Repositories\Subjects\SubjectRepon::class,
        );
        $this->app->singleton(
            \App\Repositories\Task\TaskRepositoryInterface::class,
            \App\Repositories\Task\TaskRepository::class
        );

        $this->app->singleton(
            \App\Repositories\User\UserRepositoryInterface::class,
            \App\Repositories\User\UserRepository::class,
        );
        $this->app->singleton(
            \App\Repositories\Categories\CategoryInterface::class,
            \App\Repositories\Categories\CategoryRepo::class
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton(\App\Repositories\Courses\CourseInterface::class, \App\Repositories\Courses\CourseRepository::class);
    }
}
