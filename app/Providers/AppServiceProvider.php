<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Asset;
use Illuminate\Support\Facades\View;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
          View::composer('main.main', function ($view) {
        $maintenanceModels = Asset::where('status', 'Maintenance')->get();
        $view->with('maintenanceModels', $maintenanceModels);
    });
    Paginator::useBootstrapFive();
    }
}
