<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Donate extends Model
{
    //
    protected $fillable = [
        'student_id','coordinator_id','sponsor_id','amount','send_time'
    ];

    public function pengupdate(){
        return $this->belongsTo('App\User','updater_id');
    }

    public function pembuat(){
        return $this->belongsTo('App\User','creator_id');
    }

    public function student(){
    	return $this->belongsTo('App\Student');
    }

    public function sponsor(){
    	return $this->belongsTo('App\Sponsor');
    }

    public function coordinator(){
    	return $this->belongsTo('App\Coordinator');
    }

    public function years(){
        return $this->belongsToMany('App\Year');
    }
  
    public function scopeCari($query, $filter){
        return $query->join('coordinators as c','c.id','=','donates.coordinator_id')
                        ->join('sponsors as sp','sp.id','=','donates.sponsor_id')
                        ->join('students as st','st.id','=','donates.student_id')
                        //->rightJoin('donate_year as dy','dy.donate_id','=','donates.id')
                        ->where('c.name','like','%'.$filter."%")
                        ->orWhere('st.name','like','%'.$filter."%")
                        ->orWhere('sp.name','like','%'.$filter."%");
    }
    

//========================VALIDATION RULES======================================   

    public $createRules = [
        "student_id"=>"required|integer",
        "coordinator_id"=>"required|integer",
        "sponsor_id"=>"required|integer",
        "amount"=>"required|integer",
        "send_time"=>"date",
    ];
}
