<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Card;
use Validator;
use DB;

class CardController extends Controller
{
    // ======================================== MURID ==================================
    public function index(){
        return view('content.setting.card');
    }
    public function data(Request $request){
        $rows = 10;
        if($request->input("rows") != NULL){
            $rows = $request->input("rows");
        }
        $card = Card::cari($request->input('search'))->with('pengupdate')->paginate($rows);
        return response()->json(['records'=>$card]);
    }

    public function store(Request $request){
        $card = new Card;
        $req = $request->all();
        if($req != NULL){
            $validator = Validator::make($req['postData'], $card->createRules);
            if(($validator)->fails()) return response()->json(['error'=>$validator->messages()],422);
            
            $card->fill($req['postData']);
            $card->creator_id = Auth::User()->id; $card->updater_id = Auth::User()->id;
            $card->isActive = 1;
            $card->save();
            return response()->json(["message"=>"Berhasil menambah Kartu ID","card"=>$card]);
        }
        return response()->json(["msg"=>"error"],428);
    }

    public function update(Request $request, $id){
        $card = Card::findOrFail($id);
        $req = $request->all();
        if($req != NULL){
            $validator = Validator::make($req['postData'], $card->updateRules);
            if(($validator)->fails()) return response()->json(['error'=>$validator->messages()],422);
            $card->fill($req['postData']);
            $card->updater_id = Auth::User()->id;
            $card->save();
            return response()->json(["message"=>"Berhasil mengupdate Kartu ID","card"=>$card]);
        }
        return response()->json(["msg"=>"error"],428);
    }

    public function toogleAktif($id){
        $card = Card::findOrFail($id);
        $statusString = "dinon-aktifkan";
        if($card->isActive == 0){
            $card->isActive = 1;
            $statusString = "diaktifkan";
        }
        else
            $card->isActive = 0;
        $card->updater_id = Auth::User()->id;
        $card->save();
        return response()->json(["message"=>"Kartu ID ".$statusString]);
    }
    public function show($id){
        $card = Card::findOrFail($id);
        return response()->json(["record"=>$card]);
    }

    public function destroy($id){
        $card = Card::findOrFail($id);
        $students = DB::table('students')->where('card_id',$id)->get();
        $sponsors = DB::table('sponsors')->where('card_id',$id)->get();
        $coordinators = DB::table('coordinators')->where('card_id')->get();
        if((count($students) + count($coordinators) + count($sponsors)) > 0)
            return response()->json(["message"=>"Gagal hapus kartu, hapus dulu data-data pengguna kartu ini"], 499);
        $card->delete();
        return response()->json(["message"=>"Berhasil hapus kartu"]);
    }
}
