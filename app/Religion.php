<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Religion extends Model
{
    //
    protected $fillable = [
        'religion'
    ];

    public function pengupdate(){
        return $this->belongsTo('App\User','updater_id');
    }

    public function pembuat(){
        return $this->belongsTo('App\User','creator_id');
    }

    public function scopeCari($query, $filter){
        return $query->where('religion','like','%'.$filter."%");
    }

//========================VALIDATION RULES======================================   

    public $createRules = [
        "religion"=>"required|string|max:191|unique:religions,religion",
    ];

    public $updateRules = [
        "religion"=>"required|string|max:191",
    ];
}
