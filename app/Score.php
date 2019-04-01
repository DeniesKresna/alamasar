<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Score extends Model
{
     //
    protected $fillable = [
        'score','year_id','type','student_id'
    ];
    public $incrementing = false;
    public function pengupdate(){
        return $this->belongsTo('App\User','updater_id');
    }
    public function pembuat(){
        return $this->belongsTo('App\User','creator_id');
    }
    public function year(){
        return $this->belongsTo('App\Year');
    }
    public function student(){
        return $this->belongsTo('App\Student');
    }
//========================VALIDATION RULES======================================   
    public $createRules = [
        "score"=>"required|string|max:20",
        "year_id"=>"required|integer",
    ];
    public $updateRules = [
        "score"=>"required|string|max:20",
        "year_id"=>"required|integer",
    ];
}