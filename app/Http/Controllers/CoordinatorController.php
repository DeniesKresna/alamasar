<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Coordinator;
use App\Card;
use Validator;
use Storage;
use DB;
use Auth;

class CoordinatorController extends Controller
{
    //
    public function index(){
        $cards = Card::where('isActive',1)->get();
        return view('content.coordinator.show',['cards'=>$cards]);
    }
    public function data(Request $request){
        $rows = 10;
        if($request->input("rows") != NULL){
            $rows = $request->input("rows");
        }
        $coordinator = Coordinator::cari($request->input('search'))->with('pengupdate')->with('students')->paginate($rows);
        return response()->json(['records'=>$coordinator]);
    }

    public function store(Request $request){
        $coordinator = new Coordinator;
        $req = $request->all();
        if($req != NULL){
            $validator = Validator::make($req['postData'], $coordinator->createRules);
            if(($validator)->fails()) return response()->json(['error'=>$validator->messages()],422);
            
            $coordinator->fill($req['postData']);
            $coordinator->photo = $coordinator->coordinator_numb;
            $coordinator->creator_id = Auth::User()->id; $coordinator->updater_id = Auth::User()->id;
            $coordinator->isActive = 1;
            $coordinator->save();
            if(Storage::disk('public')->exists('coordinator/'.$coordinator->photo)) 
                Storage::disk('public')->delete('coordinator/'.$coordinator->photo);
            Storage::disk('public')->copy('nofoto.jpg','coordinator/'.$coordinator->photo);
            return response()->json(["message"=>"Berhasil menambah Koordinator","coordinator"=>$coordinator]);
        }
        return response()->json(["msg"=>"error"],428);
    }

    public function update(Request $request, $id){
        $coordinator = Coordinator::findOrFail($id);
        $req = $request->all();
        if($req != NULL){
            $validator = Validator::make($req['postData'], $coordinator->updateRules);
            if(($validator)->fails()) return response()->json(['error'=>$validator->messages()],422);
            $coordinator->fill($req['postData']);
            $coordinator->updater_id = Auth::User()->id;
            $coordinator->save();
            return response()->json(["message"=>"Berhasil mengupdate Koordinator","coordinator"=>$coordinator]);
        }
        return response()->json(["msg"=>"error"],428);
    }

    public function toogleAktif($id){
        $coordinator = Coordinator::findOrFail($id);
        $statusString = "dinon-aktifkan";
        if($coordinator->isActive == 0){
            $coordinator->isActive = 1;
            $statusString = "diaktifkan";
        }
        else
            $coordinator->isActive = 0;
        $coordinator->updater_id = Auth::User()->id;
        $coordinator->save();
        return response()->json(["message"=>"Koordinator ".$statusString]);
    }
    public function show($id){
        $coordinator = DB::table('coordinators as co')->select('co.*','ca.card')->join('cards as ca','co.card_id','=','ca.id')->where('co.id',$id)->first();
        $coordinator->students = DB::table('students as s')->join('donates as d','d.student_id','=','s.id')->where('d.coordinator_id',$coordinator->id)->get();
        return response()->json(["record"=>$coordinator]);
    }

    public function changeDp(Request $request, $coordinator_numb){
        if($request->hasFile('image')){
            $this->validate($request, ["image"=>'required|image|mimes:jpeg,png,jpg,gif,svg|max:512']);
            $coordinator = Coordinator::where('coordinator_numb',$coordinator_numb)->firstOrFail();
            $imagename = $coordinator_numb.".".$request->file('image')->getClientOriginalExtension();
            Storage::disk('public')->put('/coordinator/'.$imagename,file_get_contents($request->file('image')));
            $coordinator->photo = $imagename; $coordinator->save();
            return response()->json(['message'=>"Berhasil ganti foto koordinator"]);
        }
        else
            return response()->json(['message' => "tidak ada gambar"],422);
    }

    public function destroy($id){
        $coordinator = Coordinator::findOrFail($id);
        $donates = DB::table('donates')->where('coordinator_id',$id)->get();
        if(count($donates) > 0)
            return response()->json(["message"=>"Gagal hapus koordinator, hapus dulu donasi yang dikoordinasi koordinator ini"], 499);
        if(Storage::disk('public')->exists('coordinator/'.$coordinator->photo)) 
                Storage::disk('public')->delete('coordinator/'.$coordinator->photo);
        $coordinator->delete();
        return response()->json(["message"=>"Berhasil hapus koordinator"]);
    }
}
