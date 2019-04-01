<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Eduneed extends Model
{
    //
    protected $fillable = [
        'eduNeed','price','student_id'
    ];
    public $incrementing = false;
    public function scopeCari($query, $filter){
        return $query->where('eduNeed','like','%'.$filter."%");
    }
//========================VALIDATION RULES======================================   
    public $updateRules = [
        "eduNeed"=>"required|string|max:191",
        "price"=>"required|integer",
    ];
}