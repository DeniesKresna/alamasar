<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Nation;
use Validator;
use DB;

class NationController extends Controller
{
    // ======================================== MURID ==================================
    public function index(){
        return view('content.setting.nation');
    }
    public function data(Request $request){
        $rows = 10;
        if($request->input("rows") != NULL){
            $rows = $request->input("rows");
        }
        $nation = Nation::cari($request->input('search'))->with('pengupdate')->paginate($rows);
        return response()->json(['records'=>$nation]);
    }

    public function store(Request $request){
        $nation = new Nation;
        $req = $request->all();
        if($req != NULL){
            $validator = Validator::make($req['postData'], $nation->createRules);
            if(($validator)->fails()) return response()->json(['error'=>$validator->messages()],422);
            
            $nation->fill($req['postData']);
            $nation->creator_id = Auth::User()->id; $nation->updater_id = Auth::User()->id;
            $nation->isActive = 1;
            $nation->save();
            return response()->json(["message"=>"Berhasil menambah Negara","nation"=>$nation]);
        }
        return response()->json(["msg"=>"error"],428);
    }

    public function update(Request $request, $id){
        $nation = Nation::findOrFail($id);
        $req = $request->all();
        if($req != NULL){
            $validator = Validator::make($req['postData'], $nation->updateRules);
            if(($validator)->fails()) return response()->json(['error'=>$validator->messages()],422);
            $nation->fill($req['postData']);
            $nation->updater_id = Auth::User()->id;
            $nation->save();
            return response()->json(["message"=>"Berhasil mengupdate Negara","nation"=>$nation]);
        }
        return response()->json(["msg"=>"error"],428);
    }

    public function toogleAktif($id){
        $nation = Nation::findOrFail($id);
        $statusString = "dinon-aktifkan";
        if($nation->isActive == 0){
            $nation->isActive = 1;
            $statusString = "diaktifkan";
        }
        else
            $nation->isActive = 0;
        $nation->updater_id = Auth::User()->id;
        $nation->save();
        return response()->json(["message"=>"Negara ".$statusString]);
    }
    public function show($id){
        $nation = Nation::findOrFail($id);
        return response()->json(["record"=>$nation]);
    }

    public function destroy($id){
        $nation = Nation::findOrFail($id);
        $families = DB::table('families')->where('nation_id',$id)->get();
        $sponsors = DB::table('sponsors')->where('nation_id',$id)->get();
        if((count($families) + count($sponsors)) > 0)
            return response()->json(["message"=>"Gagal hapus negara, hapus dulu data-data pengguna negara ini"], 499);
        $nation->delete();
        return response()->json(["message"=>"Berhasil hapus negara"]);
    }
}
