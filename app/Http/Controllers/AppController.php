<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use DB;

class AppController extends Controller
{
    public function errorShow(){
    	return view('content.error');
    }

    public function coordinatorsSponsors(){
        $sponsors = DB::table('sponsors')->where('isActive',1)->get();
        $coordinators = DB::table('coordinators')->where('isActive',1)->get();
        $years = DB::table('years')->get();
        return response()->json(['sponsors'=>$sponsors, 'coordinators'=>$coordinators, 'years'=>$years]);
    }

    public function coordinatorsSponsorsStudents(){
        $sponsors = DB::table('sponsors')->where('isActive',1)->get();
        $coordinators = DB::table('coordinators')->where('isActive',1)->get();
        $students = DB::table('students')->where('isActive',1)->get();
        $years = DB::table('years')->get();
        return response()->json(['sponsors'=>$sponsors, 'coordinators'=>$coordinators, 'years'=>$years, 'students'=>$students]);
    }
}

