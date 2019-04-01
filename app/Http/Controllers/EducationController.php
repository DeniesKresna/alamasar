<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Education;
use Validator;
use DB;

class EducationController extends Controller
{
    // ======================================== MURID ==================================
    public function index(){
        return view('content.setting.education');
    }
    public function data(Request $request){
        $rows = 10;
        if($request->input("rows") != NULL){
            $rows = $request->input("rows");
        }
        $education = Education::cari($request->input('search'))->with('pengupdate')->paginate($rows);
        return response()->json(['records'=>$education]);
    }

    public function store(Request $request){
        $education = new Education;
        $req = $request->all();
        if($req != NULL){
            $validator = Validator::make($req['postData'], $education->createRules);
            if(($validator)->fails()) return response()->json(['error'=>$validator->messages()],422);
            
            $education->fill($req['postData']);
            $education->creator_id = Auth::User()->id; $education->updater_id = Auth::User()->id;
            $education->isActive = 1;
            $education->save();
            return response()->json(["message"=>"Berhasil menambah Pendidikan","education"=>$education]);
        }
        return response()->json(["msg"=>"error"],428);
    }

    public function update(Request $request, $id){
        $education = Education::findOrFail($id);
        $req = $request->all();
        if($req != NULL){
            $validator = Validator::make($req['postData'], $education->updateRules);
            if(($validator)->fails()) return response()->json(['error'=>$validator->messages()],422);
            $education->fill($req['postData']);
            $education->updater_id = Auth::User()->id;
            $education->save();
            return response()->json(["message"=>"Berhasil mengupdate Pendidikan","education"=>$education]);
        }
        return response()->json(["msg"=>"error"],428);
    }

    public function toogleAktif($id){
        $education = Education::findOrFail($id);
        $statusString = "dinon-aktifkan";
        if($education->isActive == 0){
            $education->isActive = 1;
            $statusString = "diaktifkan";
        }
        else
            $education->isActive = 0;
        $education->updater_id = Auth::User()->id;
        $education->save();
        return response()->json(["message"=>"Pendidikan ".$statusString]);
    }
    public function show($id){
        $education = Education::findOrFail($id);
        return response()->json(["record"=>$education]);
    }

    public function destroy($id){
        $education = Education::findOrFail($id);
        $students = DB::table('students')->where('education_id',$id)->get();
        $families = DB::table('families')->where('education_id',$id)->get();
        if((count($students) + count($families)) > 0)
            return response()->json(["message"=>"Gagal hapus pendidikan, hapus dulu data-data pengguna jenjang pendidikan ini"], 499);
        $education->delete();
        return response()->json(["message"=>"Berhasil hapus pendidikan"]);
    }
}
