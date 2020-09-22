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
            __DIR__.'/config/guhemba-webelement.php' => config_path('guhemba-webelement.php')
        ], 'config');
    }
}