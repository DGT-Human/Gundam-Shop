<?php

namespace App\Providers;

use App\Http\View\Composers\MenuComposer;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('header', MenuComposer::class);
    }
}
