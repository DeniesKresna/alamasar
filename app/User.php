<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'sex', 'level', 'contact', 'photo'
    ];
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function scopeCari($query, $filter){
        return $query->where('email','like','%'.$filter.'%')->orWhere('contact','=','%'.$filter.'%');
    }

    public function scopeIsNotOfficial($query){
        return $query->where('level','>',2);
    }

    public function penambah(){
        return $this->belongsTo('App\User','creator_id');
    }

    public function pengupdate(){
        return $this->belongsTo('App\User','updater_id');
    }
//========================VALIDATION RULES======================================   

    public $createRules = [
        "name"=>"required|string|max:191",
        "email"=>"required|email|max:191|unique:users,email",
        "sex"=>"required|string|max:1",
        "contact"=>"string|max:30"
    ];

    public $updateRules = [
        "name"=>"required|string|max:191",
        "email"=>"required|email|max:191",
        "sex"=>"required|string|max:1",
        "contact"=>"string|max:30"
    ];
}
