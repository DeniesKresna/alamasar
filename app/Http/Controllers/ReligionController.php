<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Religion;
use Validator;
use DB;

class ReligionController extends Controller
{
    // ======================================== MURID ==================================
    public function index(){
        return view('content.setting.religion');
    }
    public function data(Request $request){
        $rows = 10;
        if($request->input("rows") != NULL){
            $rows = $request->input("rows");
        }
        $religion = Religion::cari($request->input('search'))->with('pengupdate')->paginate($rows);
        return response()->json(['records'=>$religion]);
    }

    public function store(Request $request){
        $religion = new Religion;
        $req = $request->all();
        if($req != NULL){
            $validator = Validator::make($req['postData'], $religion->createRules);
            if(($validator)->fails()) return response()->json(['error'=>$validator->messages()],422);
            
            $religion->fill($req['postData']);
            $religion->creator_id = Auth::User()->id; $religion->updater_id = Auth::User()->id;
            $religion->isActive = 1;
            $religion->save();
            return response()->json(["message"=>"Berhasil menambah Agama","religion"=>$religion]);
        }
        return response()->json(["msg"=>"error"],428);
    }

    public function update(Request $request, $id){
        $religion = Religion::findOrFail($id);
        $req = $request->all();
        if($req != NULL){
            $validator = Validator::make($req['postData'], $religion->updateRules);
            if(($validator)->fails()) return response()->json(['error'=>$validator->messages()],422);
            $religion->fill($req['postData']);
            $religion->updater_id = Auth::User()->id;
            $religion->save();
            return response()->json(["message"=>"Berhasil mengupdate Agama","religion"=>$religion]);
        }
        return response()->json(["msg"=>"error"],428);
    }

    public function toogleAktif($id){
        $religion = Religion::findOrFail($id);
        $statusString = "dinon-aktifkan";
        if($religion->isActive == 0){
            $religion->isActive = 1;
            $statusString = "diaktifkan";
        }
        else
            $religion->isActive = 0;
        $religion->updater_id = Auth::User()->id;
        $religion->save();
        return response()->json(["message"=>"Agama ".$statusString]);
    }
    public function show($id){
        $religion = Religion::findOrFail($id);
        return response()->json(["record"=>$religion]);
    }

    public function destroy($id){
        $religion = Religion::findOrFail($id);
        $families = DB::table('families')->where('religion_id',$id)->get();
        $students = DB::table('students')->where('religion_id',$id)->get();
        if((count($families) + count($students)) > 0)
            return response()->json(["message"=>"Gagal hapus agama, hapus dulu data-data pemeluk agama ini"], 499);
        $religion->delete();
        return response()->json(["message"=>"Berhasil hapus agama"]);
    }
}
