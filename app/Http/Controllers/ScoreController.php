<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Score;
use Validator;

class ScoreController extends Controller
{
    // ======================================== MURID ==================================
    public function index(){
        return view('content.setting.score');
    }
    public function data(Request $request){
        $rows = 10;
        if($request->input("rows") != NULL){
            $rows = $request->input("rows");
        }
        $score = Score::cari($request->input('search'))->with('pengupdate')->paginate($rows);
        return response()->json(['records'=>$score]);
    }

    public function store(Request $request){
        $score = new Score;
        $req = $request->all();
        if($req != NULL){
            $validator = Validator::make($req['postData'], $score->createRules);
            if(($validator)->fails()) return response()->json(['error'=>$validator->messages()],422);
            
            $score->fill($req['postData']);
            $score->creator_id = Auth::User()->id; $score->updater_id = Auth::User()->id;
            $score->isActive = 1;
            $score->save();
            return response()->json(["message"=>"Berhasil menambah Nilai","score"=>$score]);
        }
        return response()->json(["msg"=>"error"],428);
    }

    public function update(Request $request, $id){
        $score = Score::findOrFail($id);
        $req = $request->all();
        if($req != NULL){
            $validator = Validator::make($req['postData'], $score->updateRules);
            if(($validator)->fails()) return response()->json(['error'=>$validator->messages()],422);
            $score->fill($req['postData']);
            $score->updater_id = Auth::User()->id;
            $score->save();
            return response()->json(["message"=>"Berhasil mengupdate Nilai","score"=>$score]);
        }
        return response()->json(["msg"=>"error"],428);
    }

    public function toogleAktif($id){
        $score = Score::findOrFail($id);
        $statusString = "dinon-aktifkan";
        if($score->isActive == 0){
            $score->isActive = 1;
            $statusString = "diaktifkan";
        }
        else
            $score->isActive = 0;
        $score->updater_id = Auth::User()->id;
        $score->save();
        return response()->json(["message"=>"Nilai ".$statusString]);
    }
    public function show($id){
        $score = Score::findOrFail($id);
        return response()->json(["record"=>$score]);
    }
}
