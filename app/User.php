<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'password', 'email' 
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function save(array $option=[]){ // to override the parent function you have the same parameter so that
        // you passess array option[]
        // then now i say when you try to store or update a user then override the save method
        $this->api_token = Str::random(60);

    //    dump('you are trying to store or update a user ') ; 
        // but the above will not save the user yet then i will recall the original save method 
        //that exists in the parent class 
        return parent::save($option);




    }
}
