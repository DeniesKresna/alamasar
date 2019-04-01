<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    //
    protected $table = 'educations';
    protected $fillable = [
        'education'
    ];

    public function pengupdate(){
        return $this->belongsTo('App\User','updater_id');
    }

    public function pembuat(){
        return $this->belongsTo('App\User','creator_id');
    }

    public function scopeCari($query, $filter){
        return $query->where('education','like','%'.$filter."%");
    }

//========================VALIDATION RULES======================================   

    public $createRules = [
        "education"=>"required|string|max:191|unique:educations,education",
    ];

    public $updateRules = [
        "education"=>"required|string|max:191",
    ];
}
