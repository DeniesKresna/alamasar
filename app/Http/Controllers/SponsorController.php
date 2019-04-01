<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sponsor;
use App\Card;
use App\Nation;
use Validator;
use Storage;
use DB;
use Auth;

class SponsorController extends Controller
{
    //
    public function index(){
        $cards = Card::where('isActive',1)->get();
        $nations = Nation::where('isActive',1)->get();
        return view('content.sponsor.show',['cards'=>$cards,'nations'=>$nations]);
    }
    public function data(Request $request){
        $rows = 10;
        if($request->input("rows") != NULL){
            $rows = $request->input("rows");
        }
        $sponsors = Sponsor::cari($request->input('search'))->with('pengupdate')->paginate($rows);
        foreach($sponsors as $sponsor){
        	$sponsor->amount_total = DB::table('donates')->where('sponsor_id',$sponsor->id)->sum('amount');
        }
        return response()->json(['records'=>$sponsors]);
    }

    public function store(Request $request){
        $sponsor = new Sponsor;
        $req = $request->all();
        if($req != NULL){   
            $validator = Validator::make($req['postData'], $sponsor->createRules);
            if(($validator)->fails()) return response()->json(['error'=>$validator->messages()],422);
            
            $sponsor->fill($req['postData']);
            $sponsor->photo = $sponsor->sponsor_numb;
            $sponsor->creator_id = Auth::User()->id; $sponsor->updater_id = Auth::User()->id;
            $sponsor->isActive = 1;
            $sponsor->save();
            if(Storage::disk('public')->exists('sponsor/'.$sponsor->photo)) 
                Storage::disk('public')->delete('sponsor/'.$sponsor->photo);
            Storage::disk('public')->copy('nofoto.jpg','sponsor/'.$sponsor->photo);
            return response()->json(["message"=>"Berhasil menambah Sponsor","sponsor"=>$sponsor]);
        }
        return response()->json(["msg"=>"error"],428);
    }

    public function update(Request $request, $id){
        $sponsor = Sponsor::findOrFail($id);
        $req = $request->all();
        if($req != NULL){
            $validator = Validator::make($req['postData'], $sponsor->updateRules);
            if(($validator)->fails()) return response()->json(['error'=>$validator->messages()],422);
            $sponsor->fill($req['postData']);
            $sponsor->updater_id = Auth::User()->id;
            $sponsor->save();
            return response()->json(["message"=>"Berhasil mengupdate Sponsor","sponsor"=>$sponsor]);
        }
        return response()->json(["msg"=>"error"],428);
    }

    public function toogleAktif($id){
        $sponsor = Sponsor::findOrFail($id);
        $statusString = "dinon-aktifkan";
        if($sponsor->isActive == 0){
            $sponsor->isActive = 1;
            $statusString = "diaktifkan";
        }
        else
            $sponsor->isActive = 0;
        $sponsor->updater_id = Auth::User()->id;
        $sponsor->save();
        return response()->json(["message"=>"Sponsor ".$statusString]);
    }
    public function show($id){
        $sponsor = Sponsor::findOrFail($id);
        $sponsor->card = DB::table('sponsors as s')->join('cards as c','c.id','=','s.card_id')->where('s.id',$sponsor->id)->value('c.card');
        $sponsor->nation = DB::table('sponsors as s')->join('nations as n','n.id','=','s.nation_id')->where('s.id',$sponsor->id)->value('n.nation');
        return response()->json(["record"=>$sponsor]);
    }

    public function changeDp(Request $request, $sponsor_numb){
        if($request->hasFile('image')){
            $this->validate($request, ["image"=>'required|image|mimes:jpeg,png,jpg,gif,svg|max:512']);
            $sponsor = Sponsor::where('sponsor_numb',$sponsor_numb)->firstOrFail();
            $imagename = $sponsor_numb.".".$request->file('image')->getClientOriginalExtension();
            Storage::disk('public')->put('/sponsor/'.$imagename,file_get_contents($request->file('image')));
            $sponsor->photo = $imagename; $sponsor->save();
            return response()->json(['message'=>"Berhasil ganti foto sponsor"]);
        }
        else
            return response()->json(['message' => "tidak ada gambar"],422);
    }

    public function destroy($id){
        $sponsor = Sponsor::findOrFail($id);
        $donates = DB::table('donates')->where('sponsor_id',$id)->get();
        if(count($donates) > 0)
            return response()->json(["message"=>"Gagal hapus sponsor, hapus dulu donasi yang disponsori sponsor ini"], 499);

        if(Storage::disk('public')->exists('sponsor/'.$sponsor->photo)) 
            Storage::disk('public')->delete('sponsor/'.$sponsor->photo);
        $sponsor->delete();
        return response()->json(["message"=>"Berhasil hapus sponsor"]);
    }
}
