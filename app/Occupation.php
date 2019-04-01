<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Occupation extends Model
{
    //
    protected $fillable = [
        'occupation'
    ];

    public function pengupdate(){
        return $this->belongsTo('App\User','updater_id');
    }

    public function pembuat(){
        return $this->belongsTo('App\User','creator_id');
    }

    public function scopeCari($query, $filter){
        return $query->where('occupation','like','%'.$filter."%");
    }

//========================VALIDATION RULES======================================   

    public $createRules = [
        "occupation"=>"required|string|max:191|unique:occupations,occupation",
    ];

    public $updateRules = [
        "occupation"=>"required|string|max:191",
    ];
}
