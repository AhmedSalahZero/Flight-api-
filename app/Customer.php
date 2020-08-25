<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['firstName' ,'lastName'];
    public function flights(){
        return $this->belongsToMany('App\Flight' , 'flight_customer' ,'customer_id','flight_id');

    }
    
   
}
