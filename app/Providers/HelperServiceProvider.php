<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class HelperServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $helpers = \File::allFiles(app_path('Helpers/'));
        
        if (count($helpers)) {
            foreach ($helpers as $helper) {
                if (!empty($helper)) {
                    require_once $helper;
                }
            }
        }
    }
}