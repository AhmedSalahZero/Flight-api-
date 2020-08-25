<?php

namespace App\Providers\v1;
use App\services\v1\flightServices;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\facades\validator;

class FlightServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(flightServices::class , function($app){
//            $app == App();
//            $this->app == App()

            return new flightServices();

        });

       


    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        
        Validator::extend('flightStatus',function($attribute,$val){
            //attribute == 'status' in this case 
            //$val ==(its value)
            
            return $val=='ontime'|| $val =='delayed' ;

        });

        
    }
}
