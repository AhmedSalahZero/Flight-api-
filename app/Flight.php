<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    protected $fillable = ['flightNumber','arrivalAirport_id','arrivalDateTime','departureAirport_id','departureDataTime','status'];
    public function departureAirport(){
        return $this->belongsTo('App\Airport' , 'departureAirport_id' , 'id');
    }
    public function arrivalAirport(){
        return $this->belongsTo('App\Airport' , 'arrivalAirport_id' , 'id');
    }
    public function passengers(){
       return $this->belongsToMany('App\Customer','flight_customer' ,'flight_id','customer_id');
    }

}
