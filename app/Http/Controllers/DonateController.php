<?php

namespace App\Http\Controllers;

use App\Exports\DonatesExport;
use Illuminate\Http\Request;
use App\Donate;
use Validator;
use Storage;
use DB;
use Auth;
use Excel;

class DonateController extends Controller
{
    //
     //
    public function index(){
        $donates = Donate::all();
        return view('content.donate.show',['donates'=>$donates]);
    }
    public function data(Request $request){
        $rows = 10;
        if($request->input("rows") != NULL){
            $rows = $request->input("rows");
        }
        $filter = $request->input("search");
        $donates = DB::table('donates as d')->select('d.id as donate_id','d.sponsor_id','d.student_id','d.coordinator_id','d.updater_id', 'd.amount')->join('coordinators as c','c.id','=','d.coordinator_id')
                        ->join('sponsors as sp','sp.id','=','d.sponsor_id')
                        ->join('students as st','st.id','=','d.student_id')
                        ->where('c.name','like','%'.$filter."%")
                        ->orWhere('st.name','like','%'.$filter."%")
                        ->orWhere('sp.name','like','%'.$filter."%")->paginate($rows);
        foreach ($donates as $donate) {
            $donate->pengupdate = DB::table('users')->where('id',$donate->updater_id)->first();
            $donate->student = DB::table('students')->where('id',$donate->student_id)->first();
            $donate->sponsor = DB::table('sponsors')->where('id',$donate->sponsor_id)->first();
            $donate->coordinator = DB::table('coordinators')->where('id',$donate->coordinator_id)->first();
            $donate->years = DB::table('years as y')->join('donate_year as dy','dy.year_id','=','y.id')->where('donate_id',$donate->donate_id)->get();
        }
        
        return response()->json(['records'=>$donates]);
    }

    public function store(Request $request){
        $donate = new Donate;
        $req = $request->all();
        //return response()->json(['req'=>$req],422);
        if($req != NULL){
            $inputdata = [
                "student_id"=>$req['student_id'],
                "coordinator_id"=>$req['coordinator_id'],
                "sponsor_id"=>$req['sponsor_id'],
                "amount"=>$req['amount'],
                "send_time"=>$req['send_time'],
            ];
            $validator = Validator::make($inputdata , $donate->createRules);
            if(($validator)->fails()) return response()->json(['error'=>$validator->messages()],422);

            /*$donate = Donate::updateOrCreate(["student_id"=>$req['student_id'],"coordinator_id"=>$req['coordinator_id'],
                "sponsor_id"=>$req['sponsor_id']],$inputdata);*/
            $donate = Donate::create($inputdata);
            $donate->creator_id = Auth::User()->id; $donate->updater_id = Auth::User()->id;
            $donate->isVerified = 1;

            if($request->hasFile('image')){
                $donate->photo = $req['student_id'].'-'.$req['coordinator_id'].'-'.$req['sponsor_id'].'-'.$req['send_time'].'.'.$request->file('image')->getClientOriginalExtension();
                if(Storage::disk('public')->exists('donate/'.$donate->photo)) 
                    Storage::disk('public')->delete('donate/'.$donate->photo);
                Storage::disk('public')->put('/donate/'.$donate->photo,file_get_contents($request->file('image')));
            }
            else{
                if($donate->photo == null || $donate->photo == '')
                    $donate->photo = 'nofoto.jpg';
            }

            $donate->save();

            $yearlist = $req["years"];
            $donate->years()->sync($yearlist);
            return response()->json(["message"=>"Berhasil modifikasi Donasi","record"=>$donate->with('sponsor')->with('coordinator')->with('years')->get()]);
        }
        return response()->json(["msg"=>"error"],428);
    }

    public function student($id){
        $donates = Donate::where('student_id',$id);
        $student_amount = DB::table('eduneeds')->where('student_id',$id)->sum('price');
        return response()->json(['donates'=>$donates->with('coordinator')->with('sponsor')->with('years')->get(), 'student_amount'=>$student_amount]);
    }

    public function toogleAktif($id){
        $donate = Donate::findOrFail($id);
        $statusString = "dinon-aktifkan";
        if($donate->isActive == 0){
            $donate->isActive = 1;
            $statusString = "diaktifkan";
        }
        else
            $donate->isActive = 0;
        $donate->updater_id = Auth::User()->id;
        $donate->save();
        return response()->json(["message"=>"Koordinator ".$statusString]);
    }
    public function show($id){
        $donate = DB::table('donates')->where('id',$id)->first();
        $donate->pengupdate = DB::table('users')->where('id',$donate->updater_id)->first();
        $donate->student = DB::table('students')->where('id',$donate->student_id)->first();
        $donate->sponsor = DB::table('sponsors')->where('id',$donate->sponsor_id)->first();
        $donate->coordinator = DB::table('coordinators')->where('id',$donate->coordinator_id)->first();
        $donate->years = DB::table('years as y')->join('donate_year as dy','dy.year_id','=','y.id')->where('donate_id',$donate->id)->get();
        return response()->json(["record"=>$donate]);
    }

    public function destroy($id){
        $donate = Donate::findOrFail($id);
        DB::table('donate_year')->where('donate_id',$id)->delete();
        if(Storage::disk('public')->exists('donate/'.$donate->photo)) 
            Storage::disk('public')->delete('donate/'.$donate->photo);        
        $donate->delete();
        return response()->json(["message"=>"Berhasil hapus donasi ini"]);
    }

    public function changeDp(Request $request, $id){
        if($request->hasFile('image')){
            $this->validate($request, ["image"=>'required|image|mimes:jpeg,png,jpg,gif,svg|max:512']);
            $donate = Donate::findOrFail($id);
            $imagename = $id.".".$request->file('image')->getClientOriginalExtension();
            Storage::disk('public')->put('/donate/'.$imagename,file_get_contents($request->file('image')));
            $donate->photo = $imagename; $donate->save();
            return response()->json(['message'=>"Berhasil ganti nota donasi"]);
        }
        else
            return response()->json(['message' => "tidak ada gambar"],422);
    }

    public function export(Request $request) 
    {
        return Excel::download(new DonatesExport($request->input('search')), 'donate.xlsx');
    }
}
