<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Year extends Model
{
    //
    protected $fillable = [
        'year'
    ];

    public function pengupdate(){
        return $this->belongsTo('App\User','updater_id');
    }

    public function pembuat(){
        return $this->belongsTo('App\User','creator_id');
    }

    public function scopeCari($query, $filter){
        return $query->where('year','like','%'.$filter."%");
    }

//========================VALIDATION RULES======================================   

    public $createRules = [
        "year"=>"required|string|max:191|unique:years,year",
    ];

    public $updateRules = [
        "year"=>"required|string|max:191",
    ];
}
