<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Airport extends Model
{
   protected $fillable = ['iataCode','city','province'];

   public function arrivingFlights(){
       return $this->hasMany('App\Flight' , 'arrivalAirport_id' , 'id');
   }

    public function departingFlights(){
        return $this->hasMany('App\Flight' , 'departureAirport_id' , 'id');
    }

}
