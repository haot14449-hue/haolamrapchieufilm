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
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            static $headerGenres = null;
            if ($headerGenres === null) {
                try {
                    $headerGenres = \Illuminate\Support\Facades\Schema::hasTable('genres')
                        ? \App\Models\Genre::orderBy('name', 'asc')->get()
                        : collect();
                } catch (\Throwable $e) {
                    $headerGenres = collect();
                }
            }
            $view->with('headerGenres', $headerGenres);
        });
    }
}
