<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Airport;
use App\Customer;
use App\Flight;

use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Airport::class, function (Faker $faker) {

    return [
        'iataCode'=>Str::random(3),
        'city'=>$faker->city ,
        'province'=>$faker->state
    ];
});

$factory->define(Customer::class , function (Faker $faker){
   return [
        'firstName'=>$faker->firstName ,
       'lastName'=>$faker->lastName
    ];
});

$factory->define(Flight::class , function(Faker $faker){
    $flightHours = $faker->numberBetween(1,5) ;
    $flightTime = new DateInterval('PT'.$flightHours.'H');
    $arrival = $faker->dateTime();
    $depart = clone $arrival ;
    $depart->sub($flightTime);

    return[
'flightNumber'=>Str::random(3) . $faker->unique()->randomNumber(5)  ,
        'arrivalAirport_id'=>$faker->numberBetween(1,5) ,
        'arrivalDateTime'=>$arrival  ,
        'departureAirport_id'=>$faker->numberBetween(1,5) ,
        'departureDataTime'=>$depart   ,
        'status'=>$faker->boolean ? 'onTime' :'delayed'
    ];
});
