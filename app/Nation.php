<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Nation extends Model
{
    //
    protected $fillable = [
        'nation'
    ];

    public function pengupdate(){
        return $this->belongsTo('App\User','updater_id');
    }

    public function pembuat(){
        return $this->belongsTo('App\User','creator_id');
    }

    public function scopeCari($query, $filter){
        return $query->where('nation','like','%'.$filter."%");
    }

//========================VALIDATION RULES======================================   

    public $createRules = [
        "nation"=>"required|string|max:191|unique:nations,nation",
    ];

    public $updateRules = [
        "nation"=>"required|string|max:191",
    ];
}
