<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Family;

class FamilyController extends Controller
{
    //
    public function show($id){
    	$family = Family::findOrFail($id);
    	return response()->json(["record"=>$family]);
    }

    public function showByNik($nik){
    	$family = Family::where('nik',$nik)->firstOrFail();
    	return response()->json(["record"=>$family]);
    }
}
