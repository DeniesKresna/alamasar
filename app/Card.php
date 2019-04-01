<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    //
    protected $fillable = [
        'card'
    ];

    public function pengupdate(){
        return $this->belongsTo('App\User','updater_id');
    }

    public function pembuat(){
        return $this->belongsTo('App\User','creator_id');
    }

    public function scopeCari($query, $filter){
        return $query->where('card','like','%'.$filter."%");
    }

//========================VALIDATION RULES======================================   

    public $createRules = [
        "card"=>"required|string|max:191|unique:cards,card",
    ];

    public $updateRules = [
        "card"=>"required|string|max:191",
    ];
}
