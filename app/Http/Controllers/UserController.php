<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\User;
use App\Exports\MuridsExport;
use App\Imports\MuridsImport;
use Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Auth;
use DB;

class UserController extends Controller
{
    // ======================================== MURID ==================================
    public function index(){
        return view('content.user.show');
    }
    public function data(Request $request){
        $rows = 10;
        if($request->input("rows") != NULL){
            $rows = $request->input("rows");
        }
        $user = User::cari($request->input('search'))->paginate($rows);
        return response()->json(['records'=>$user]);
    }

    public function store(Request $request){
        $user = new User;
        if(Auth::user()->level != 1){
            if($request->has('postData.level'))
                $request->merge(['postData.level' => 3]);
        }

        $req = $request->all();
        if($req != NULL){
            $validator = Validator::make($req['postData'], $user->createRules);
            if(($validator)->fails()) return response()->json(['error'=>$validator->messages()],422);
            
            $user->fill($req['postData']);
            $user->password = bcrypt('12345');
            $user->creator_id = Auth::user()->id; $user->updater_id = Auth::user()->id;
            $user->photo = $user->id.'.jpg';
            $user->isActive = 1;
            $user->save();
            if(Storage::disk('public')->exists('dp/'.$user->photo)) 
                Storage::disk('public')->delete('dp/'.$user->photo);
            Storage::disk('public')->copy('nofoto.jpg','dp/'.$user->photo);
            return response()->json(["message"=>"Berhasil menambah user","user"=>$user]);
        }
        return response()->json(["msg"=>"error"],428);
    }

    public function update(Request $request, $id){
        $user = User::findOrFail($id);
        $req = $request->all();
        if(Auth::user()->level != 1){
            if($req['postData']['level'] == 1)
                return response()->json(["msg"=>"Yang menjadikan admin hanyalah admin"],428);
        }
        if($req != NULL){
            $validator = Validator::make($req['postData'], $user->updateRules);
            if(($validator)->fails()) return response()->json(['error'=>$validator->messages()],422);
            $user->fill($req['postData']);
            $user->creator_id = Auth::user()->id; $user->updater_id = Auth::user()->id;
            $user->update();
            return response()->json(["message"=>"Berhasil mengupdate user","user"=>$user]);
        }
        return response()->json(["msg"=>"error"],428);
    }

    public function toogleAktif($id){
        $user = User::findOrFail($id);
        $statusString = "dinon-aktifkan";
        if($user->isActive == 0){
            $user->isActive = 1;
            $statusString = "diaktifkan";
        }
        else
            $user->isActive = 0;
        $user->updater_id = Auth::User()->id;
        $user->save();
        return response()->json(["message"=>"User ".$statusString]);
    }
    public function show($id){
        $user = User::findOrFail($id);
        return response()->json(["record"=>$user]);
    }
    public function profile($id){
        $user = User::findOrFail($id);
        if(Auth::User()->level != 1 && $user->level == 1){
            abort(403);
        }
        return view('content.user.profile');
    }
    public function profiledata($id){
        $user = DB::table('users')->select('name', 'email', 'sex', 'level', 'contact', 'photo', 'creator_id', 'updater_id')->where('id',$id)->first();
        $user->penambah = DB::table('users')->select('name', 'email', 'sex', 'contact', 'photo')->where('id',$user->creator_id)->first();
        $user->pengupdate = DB::table('users')->select('name', 'email', 'sex', 'contact', 'photo')->where('id',$user->updater_id)->first();
        return response()->json(['record'=>$user]);
    }
    public function changeDp(Request $request, $id){
        if($request->hasFile('image')){
            $this->validate($request, ["image"=>'required|image|mimes:jpeg,png,jpg,gif,svg|max:512']);
            $user = User::findOrFail($id);
            $imagename = $id.".".$request->file('image')->getClientOriginalExtension();
            Storage::disk('public')->put('/dp/'.$imagename,file_get_contents($request->file('image')));
            $user->photo = $imagename; $user->save();
            return response()->json(['message'=>"Berhasil ganti foto user"]);
        }
        else
            return response()->json(['message' => "tidak ada gambar"],422);
    }
    public function changePassword(Request $request){
        $user = Auth::user();
        $req = $request->all();
        if($req != NULL){
            $validator = Validator::make($req['postData'], ['new_password'=>'required|max:191']);
            if(($validator)->fails()) return response()->json(['error'=>$validator->messages()],422);
            if(!Hash::check($req['postData']['old_password'], $user->password)){
                return response()->json(["msg"=>"password lama anda tidak cocok"],428);
            }
            $user->password = bcrypt($req['postData']['new_password']);
            $user->save();
            return response()->json(['message'=>"Berhasil ganti password"]);
        }  
        return response()->json(["msg"=>"error"],428);
    }
    public function destroy($id){
        $user = User::findOrFail($id);
        $donates = DB::table('donates')->where('updater_id',$id)->get();
        if(count($donates) > 0)
            return response()->json(["message"=>"Gagal hapus user, hapus dulu donasi yang dimodifikasi user ini"], 499);
        $user->delete();
        return response()->json(["message"=>"Berhasil hapus user"]);
    }
}
