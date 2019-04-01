<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Year;
use Validator;
use DB;
use Auth;

class YearController extends Controller
{
    // ======================================== MURID ==================================
    public function index(){
        return view('content.setting.year');
    }
    public function data(Request $request){
        $rows = 10;
        if($request->input("rows") != NULL){
            $rows = $request->input("rows");
        }
        $year = Year::cari($request->input('search'))->with('pengupdate')->paginate($rows);
        return response()->json(['records'=>$year]);
    }

    public function store(Request $request){
        $year = new Year;
        $req = $request->all();
        if($req != NULL){
            $validator = Validator::make($req['postData'], $year->createRules);
            if(($validator)->fails()) return response()->json(['error'=>$validator->messages()],422);
            
            $year->fill($req['postData']);
            $year->creator_id = Auth::User()->id; $year->updater_id = Auth::User()->id;
            $year->isActive = 1;
            $year->save();
            return response()->json(["message"=>"Berhasil menambah Tahun Ajaran","year"=>$year]);
        }
        return response()->json(["msg"=>"error"],428);
    }

    public function update(Request $request, $id){
        $year = Year::findOrFail($id);
        $req = $request->all();
        if($req != NULL){
            $validator = Validator::make($req['postData'], $year->updateRules);
            if(($validator)->fails()) return response()->json(['error'=>$validator->messages()],422);
            $year->fill($req['postData']);
            $year->updater_id = Auth::User()->id;
            $year->save();
            return response()->json(["message"=>"Berhasil mengupdate Tahun Ajaran","year"=>$year]);
        }
        return response()->json(["msg"=>"error"],428);
    }

    public function toogleAktif($id){
        $year = Year::findOrFail($id);
        $statusString = "dinon-aktifkan";
        if($year->isActive == 0){
            $year->isActive = 1;
            $statusString = "diaktifkan";
        }
        else
            $year->isActive = 0;
        $year->updater_id = Auth::User()->id;
        $year->save();
        return response()->json(["message"=>"Tahun Ajaran ".$statusString]);
    }
    public function show($id){
        $year = Year::findOrFail($id);
        return response()->json(["record"=>$year]);
    }
    public function list(){
        $years = Year::all();
        return response()->json(['years'=>$years]);
    }

    public function destroy($id){
        $year = Year::findOrFail($id);
        $scores = DB::table('scores')->where('year_id',$id)->get();
        $donate_years = DB::table('donate_year')->where('year_id',$id)->get();
        if((count($scores) + count($donate_years)) > 0)
            return response()->json(["message"=>"Gagal hapus tahun ajaran, hapus dulu data-data di tahun ajaran ini"], 499);
        $year->delete();
        return response()->json(["message"=>"Berhasil hapus tahun ajaran"]);
    }
}
