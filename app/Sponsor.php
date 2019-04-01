<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sponsor extends Model
{
    //
    protected $fillable = [
        'sponsor_numb','card_id','id_numb','name','address','contact','photo','email','account_numb','account_name','bank_name','bank_branch','bank_city','type','nation_id','job_desc','start_date'
    ];

    public function pengupdate(){
        return $this->belongsTo('App\User','updater_id');
    }

    public function pembuat(){
        return $this->belongsTo('App\User','creator_id');
    }
    
    public function scopeCari($query, $filter){
        return $query->where('name','like','%'.$filter."%");
    }

//========================VALIDATION RULES======================================   

    public $createRules = [
        "name"=>"required|string|max:191",
        "type"=>"max:191",
        "id_numb"=>"max:191",
        "card_id"=>"max:191",
        "nation_id"=>"required|integer",
        "sponsor_numb"=>"required|string|max:191|unique:sponsors,sponsor_numb",
        "address"=>"max:191",
        "photo" => "max:191",
        "email" => "max:191",
        "contact" => "max:191",
        "account_numb" => "max:191",
        "account_name" => "max:191",
        "bank_name" => "max:191",
        "bank_branch" => "max:191",
        "bank_city" => "max:191",
        "job_desc" => "max:300",
        "start_date" => "date"
    ];

    public $updateRules = [
        "name"=>"required|string|max:191",
        "type"=>"max:191",
        "id_numb"=>"max:191",
        "card_id"=>"max:191",
        "nation_id"=>"required|integer",
        "sponsor_numb"=>"required|string|max:191",
        "address"=>"max:191",
        "photo" => "max:191",
        "email" => "max:191",
        "contact" => "max:191",
        "account_numb" => "max:191",
        "account_name" => "max:191",
        "bank_name" => "max:191",
        "bank_branch" => "max:191",
        "bank_city" => "max:191",
        "job_desc" => "max:300",
        "start_date" => "date"
    ];
}
