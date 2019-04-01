<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Coordinator extends Model
{
    //
    protected $fillable = [
        'coordinator_numb','card_id','id_numb','name','address','contact','photo','email','city','sex','account_numb','account_name','bank_name','bank_branch','bank_city','job_desc','isActive','note'
    ];

    public function pengupdate(){
        return $this->belongsTo('App\User','updater_id');
    }

    public function pembuat(){
        return $this->belongsTo('App\User','creator_id');
    }

    public function students(){
    	return $this->belongsToMany('App\Student','donates');
    }
    
    public function scopeCari($query, $filter){
        return $query->where('name','like','%'.$filter."%")
        			->orWhere('city','like','%'.$filter.'%');
    }

//========================VALIDATION RULES======================================   

    public $createRules = [
        "name"=>"required|string|max:191",
        "id_numb"=>"required|string|max:191|unique:coordinators,id_numb",
        "card_id"=>"required|string|max:191",
        "coordinator_numb"=>"required|string|unique:coordinators,coordinator_numb",
        "sex"=>"max:1",
        "address"=>"max:191",
        "city" => "max:191",
        "photo" => "max:191",
        "email" => "max:191",
        "contact" => "max:191",
        "account_numb" => "max:191",
        "account_name" => "max:191",
        "bank_name" => "max:191",
        "bank_branch" => "max:191",
        "bank_city" => "max:191",
        "job_desc" => "max:300",
        "note" => "max:191"
    ];

    public $updateRules = [
        "name"=>"required|string|max:191",
        "id_numb"=>"required|string|max:191",
        "card_id"=>"required|string|max:191",
        "coordinator_numb"=>"required|string",
        "sex"=>"max:1",
        "address"=>"max:191",
        "city" => "max:191",
        "photo" => "max:191",
        "email" => "max:191",
        "contact" => "max:191",
        "account_numb" => "max:191",
        "account_name" => "max:191",
        "bank_name" => "max:191",
        "bank_branch" => "max:191",
        "bank_city" => "max:191",
        "job_desc" => "max:300",
        "note"=>"max:191"
    ];
}
