<?php

namespace App\Providers;

use App\Repositories\Directory\DirectoryEloquentRepository;
use App\Repositories\Directory\DirectoryRepositoryInterface;
use App\Repositories\Files\FilesEloquentRepository;
use App\Repositories\Files\FilesRepositoryInterface;
use App\Repositories\JobRepository\JobEloquentRepository;
use App\Repositories\JobRepository\JobRepositoryInterface;
use App\Repositories\User\UserEloquentRepository;
use App\Repositories\User\UserRepositoryInterface;
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
        $this->app->bind(
            UserRepositoryInterface::class,
            UserEloquentRepository::class
        );

        $this->app->bind(
            FilesRepositoryInterface::class,
            FilesEloquentRepository::class
        );
        $this->app->bind(
            DirectoryRepositoryInterface::class,
            DirectoryEloquentRepository::class
        );
        $this->app->bind(
            JobRepositoryInterface::class,
            JobEloquentRepository::class
        );
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
