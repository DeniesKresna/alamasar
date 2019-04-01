<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class Student extends Model
{
    protected $guarded = [
        'id','creator_id','updater_id','created_at','updated_at'
    ];
    public function families(){
        return $this->belongsToMany('App\Family')->withPivot('type');
    }
    public function eduneeds(){
        return $this->hasMany('App\Eduneeds');
    }
    public function religion(){
        return $this->belongsTo('App\Religion');
    }
    public function education(){
        return $this->belongsTo('App\Education');
    }
    public function card(){
        return $this->belongsTo('App\Card');
    }
    public function scores(){
        return $this->hasMany('App\Score');
    }
    public function scopeFilterData($query, $search){
        return $query->where('name','like','%'.$search.'%')
                    ->orWhere('student_numb','like','%'.$search.'%')
                    ->orWhere('card_id','like','%'.$search.'%')
                    ->orWhere('status','like','%'.$search.'%')
                    ->orWhere('education_desc','like','%'.$search.'%');
    }
//========================VALIDATION RULES======================================   
    public $createRules = [
        "student_numb"=>"required|string|max:191|unique:students,student_numb",
        "id_numb"=>"required|string|max:191|unique:students,id_numb",
        "card_id"=>"required|integer",
        "name"=>"required|string|max:191",
        "sex"=>"required|string|max:1",
        "address"=>"max:191",
        "domicile"=>"max:191",
        "birth_place"=>"max:191",  
        "birth_date"=>"date",  
        "religion_id"=>"required|integer",
        "status"=>"max:50",
        "status_desc"=>"max:191",
        "note"=>"max:191",
        "contact" => "max:191",
        "email" => "max:191",
        "education_id" => "required|integer",
        "education_desc" => "max:191",
        "account_numb" => "max:191",
        "bank_name" => "max:191",
        "bank_branch" => "max:191",
        "bank_city" => "max:191",
        "account_name" => "max:191",
        "cc_numb" => "max:191"
    ];
    public $updateRules = [
        "student_numb"=>"required|string|max:191",
        "id_numb"=>"required|string|max:191",
        "card_id"=>"required|integer",
        "name"=>"required|string|max:191",
        "sex"=>"required|string|max:1",
        "address"=>"max:191",
        "domicile"=>"max:191",
        "birth_place"=>"max:191",  
        "birth_date"=>"date",  
        "religion_id"=>"required|integer",
        "status"=>"max:50",
        "status_desc"=>"max:191",
        "note"=>"max:191",
        "contact" => "max:191",
        "email" => "max:191",
        "education_id" => "required|integer",
        "education_desc" => "max:191",
        "account_numb" => "max:191",
        "bank_name" => "max:191",
        "bank_branch" => "max:191",
        "bank_city" => "max:191",
        "account_name" => "max:191",
        "cc_numb" => "max:191"
    ];
}