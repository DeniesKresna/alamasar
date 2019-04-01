<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Occupation;
use Validator;
use DB;

class OccupationController extends Controller
{
    // ======================================== MURID ==================================
    public function index(){
        return view('content.setting.occupation');
    }
    public function data(Request $request){
        $rows = 10;
        if($request->input("rows") != NULL){
            $rows = $request->input("rows");
        }
        $occupation = Occupation::cari($request->input('search'))->with('pengupdate')->paginate($rows);
        return response()->json(['records'=>$occupation]);
    }

    public function store(Request $request){
        $occupation = new Occupation;
        $req = $request->all();
        if($req != NULL){
            $validator = Validator::make($req['postData'], $occupation->createRules);
            if(($validator)->fails()) return response()->json(['error'=>$validator->messages()],422);
            
            $occupation->fill($req['postData']);
            $occupation->creator_id = Auth::User()->id; $occupation->updater_id = Auth::User()->id;
            $occupation->isActive = 1;
            $occupation->save();
            return response()->json(["message"=>"Berhasil menambah Pekerjaan","occupation"=>$occupation]);
        }
        return response()->json(["msg"=>"error"],428);
    }

    public function update(Request $request, $id){
        $occupation = Occupation::findOrFail($id);
        $req = $request->all();
        if($req != NULL){
            $validator = Validator::make($req['postData'], $occupation->updateRules);
            if(($validator)->fails()) return response()->json(['error'=>$validator->messages()],422);
            $occupation->fill($req['postData']);
            $occupation->updater_id = Auth::User()->id;
            $occupation->save();
            return response()->json(["message"=>"Berhasil mengupdate Pekerjaan","occupation"=>$occupation]);
        }
        return response()->json(["msg"=>"error"],428);
    }

    public function toogleAktif($id){
        $occupation = Occupation::findOrFail($id);
        $statusString = "dinon-aktifkan";
        if($occupation->isActive == 0){
            $occupation->isActive = 1;
            $statusString = "diaktifkan";
        }
        else
            $occupation->isActive = 0;
        $occupation->updater_id = Auth::User()->id;
        $occupation->save();
        return response()->json(["message"=>"Pekerjaan ".$statusString]);
    }
    public function show($id){
        $occupation = Occupation::findOrFail($id);
        return response()->json(["record"=>$occupation]);
    }

    public function destroy($id){
        $occupation = Occupation::findOrFail($id);
        $families = DB::table('families')->where('occupation_id',$id)->get();
        if(count($families) > 0)
            return response()->json(["message"=>"Gagal hapus pekerjaan, hapus dulu data-data pengguna pekerjaan ini"], 499);
        $occupation->delete();
        return response()->json(["message"=>"Berhasil hapus pekerjaan"]);
    }
}
