<?php

use App\Airport;
use App\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        factory('App\Airport' , 5)->create() ;

        factory('App\Flight',10)->create()->each(function ($flight){
            //flight number 2
           factory('App\Customer',100)->create()->each(function ($customer) use ($flight) {

               //customer 1
               $flight->passengers()->save($customer);
           });
        });












//        factory('App\Airport',5)->create() ;
//
//        factory('App\Flight' ,10)->create()->each(function ($flight){
//           factory('App\Customer',100)->create()->each(function ($customer) use ($flight){
//               $flight->passengers()->save($customer);
//
//           }) ;
//        });


    }
}
