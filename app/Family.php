<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class Family extends Model
{
    protected $fillable = ['nik','name','address','birth_place','birth_date','religion_id','contact','email','education_id','occupation_id','occupation_desc','marital_status','isDied'];
    public $timestamps = false;
    public function students(){
        return $this->belongsToMany('App\Student');
    }
    public $createRules = [
        "nik"=>"required|string|max:191|unique:families,nik",
        "name"=>"required|string|max:191",
        "address"=>"max:191",
        "birth_place"=>"max:191",  
        "birth_date"=>"date",  
        "religion_id"=>"required|integer",
        "contact" => "max:191",
        "email" => "max:191",
        "education_id" => "required|integer",
        "occupation_id" => "required|integer",
        "occupation_desc" => "max:191",
        "marital_status" => "required|string|max:191",
        "isDied" => "required|integer"
    ];
    public $updateRules = [
        "nik"=>"required|string|max:191",
        "name"=>"required|string|max:191",
        "address"=>"max:191",
        "birth_place"=>"max:191",  
        "birth_date"=>"date",  
        "religion_id"=>"required|integer",
        "contact" => "max:191",
        "email" => "max:191",
        "education_id" => "required|integer",
        "occupation_id" => "required|integer",
        "occupation_desc" => "max:191",
        "marital_status" => "required|string|max:191",
        "isDied" => "required|integer"
    ];
}