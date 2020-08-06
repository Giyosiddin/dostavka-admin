<?php

namespace App\Providers;

use Laravel\Nova\Nova;
use Laravel\Nova\Cards\Help;
use Illuminate\Support\Facades\Gate;
use Laravel\Nova\NovaApplicationServiceProvider;
use Bakerkretzmar\NovaSettingsTool\SettingsTool;
use Illuminate\Support\Facades\Event;

use App\Order;
use \App\Observers\OrderObserver;
use App\Nova\Metrics\NewOrders;
use App\Nova\Metrics\NewProducts;
use App\Nova\Metrics\NewOrdersTotal;

use App\Nova\Metrics\OrdersPerDay; 
use App\Nova\Metrics\OrdersTotalPerDay;
use App\Nova\Metrics\ProductsPerDay;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
        Order::observe(OrderObserver::class);
 
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
                ->withAuthenticationRoutes()
                ->withPasswordResetRoutes()
                ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewNova', function ($user) {
            return in_array($user->email, [
                //
            ]);
        });
    }

    /**
     * Get the cards that should be displayed on the Nova dashboard.
     *
     * @return array
     */
    protected function cards()
    {
        return [
            // new Help,
            new NewOrders,
            new NewOrdersTotal,
            new NewProducts,
            new OrdersPerDay, 
            new OrdersTotalPerDay,
            new ProductsPerDay
        ];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        return [
            new SettingsTool,
        ];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
