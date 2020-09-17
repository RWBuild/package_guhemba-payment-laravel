<?php
namespace RWBuild\Guhemba;

use Illuminate\Support\ServiceProvider;

class GuhembaServiceProvider extends ServiceProvider
{
   
    public function register()
    {
        $this->app->bind('Guhemba', GuhembaPayment::class);
    }

    
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/guhemba.php' => config_path('guhemba.php')
        ], 'config');
    }
}