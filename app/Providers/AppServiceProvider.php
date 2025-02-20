<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            \App\Domain\Repositories\Movie\IMovieRepository::class,
            \App\Domain\Repositories\Movie\MovieRepository::class
        ); 

        $this->app->singleton(
            \App\Domain\Repositories\Showtime\IShowtimeRepository::class,
            \App\Domain\Repositories\Showtime\ShowtimeRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
