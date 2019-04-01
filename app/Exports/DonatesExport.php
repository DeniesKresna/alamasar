<?php

namespace App\Exports;

use DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class DonatesExport implements FromView
{
	public function __construct($filter)
    {
        $this->filter = $filter;
    }

    public function view(): View
    {
        $donates = DB::table('donates as d')->select('d.id as donate_id','d.sponsor_id','d.student_id','d.coordinator_id','d.updater_id', 'd.amount', 'd.send_time', 'd.updated_at')->join('coordinators as c','c.id','=','d.coordinator_id')
                        ->join('sponsors as sp','sp.id','=','d.sponsor_id')
                        ->join('students as st','st.id','=','d.student_id')
                        ->where('c.name','like','%'.$this->filter."%")
                        ->orWhere('st.name','like','%'.$this->filter."%")
                        ->orWhere('sp.name','like','%'.$this->filter."%")->get();
        foreach ($donates as $donate) {
            $donate->pengupdate = DB::table('users')->where('id',$donate->updater_id)->first();
            $donate->student = DB::table('students')->where('id',$donate->student_id)->first();
            $donate->sponsor = DB::table('sponsors')->where('id',$donate->sponsor_id)->first();
            $donate->coordinator = DB::table('coordinators')->where('id',$donate->coordinator_id)->first();
            $donate->years = DB::table('years as y')->join('donate_year as dy','dy.year_id','=','y.id')->where('donate_id',$donate->donate_id)->get();
        }

        return view('content.donate.export',['donates'=>$donates]);
    }
}
