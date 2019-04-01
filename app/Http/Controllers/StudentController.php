<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Religion;
use App\Student;
use App\Family;
use App\Nation;
use App\Education;
use App\Occupation;
use App\Card;
use App\Year;
use App\Score;
use App\Eduneed;
use App\Donate;
use DB;
use Storage;
use Validator;
use Auth;
class StudentController extends Controller
{
    //
    public function index(){
        return view('content.student.show');
    }

    public function data(Request $request){
        $rows = 20;
        $search = $request->input('search');
        $sortfield = $request->input('sortfield');
        $sortmode = $request->input('sortmode');
        if($sortfield == NULL)
            $sortfield = 'student_numb';
        if($sortmode == NULL)
            $sortmode = 'asc';
        if($request->input('rows') != NULL)
            $rows = $request->input('rows');
        $students = Student::filterData($search)->with(['scores' => function ($query) {
            $query->select('scores.*','y.year')->join('years as y','y.id','=','scores.year_id')->orderBy('y.year', 'asc');
        }])->orderBy($sortfield,$sortmode)->paginate($rows);
        return response()->json(['records'=>$students]);
    }

    public function show($id){
        $student = Student::where('id',$id)->firstOrFail();
        $scores = [];
        $student_scores = DB::table('scores as sc')->select('sc.*','y.year')->join('students as st','st.id','=','sc.student_id')->join('years as y','y.id','=','sc.year_id')->where('st.id',$id)->orderBy('y.year')->get();
        foreach($student_scores as $score){
            if($score->type == 'ip'){
                $score->aliasScore = intval(floatval($score->score) / 4 * 100);
            }
            else{
                $score->aliasScore = intval($score->score);
            }
            array_push($scores, $score);
        }
        $student->scores = $scores;
        return response()->json(['student' => $student]);
    }

    public function add(){
        $religions = Religion::where('isActive','>',0)->get();
        $nations = Nation::where('isActive','>',0)->get();
        $educations = Education::where('isActive','>',0)->get();
        $occupations = Occupation::where('isActive','>',0)->get();
        $cards = Card::where('isActive','>',0)->get();
        $years = Year::where('isActive','>',0)->get();
        return view('content.student.add', ['cards'=>$cards, 'religions'=>$religions, 'nations' => $nations, 'educations' => $educations,'occupations' => $occupations, 'years' => $years]);
    }

    public function studentFamily($id){
        $student = Student::findOrFail($id);
        $families = DB::table('families as f')->select('f.*','fs.type')->join('family_student as fs','fs.family_id','f.id')->join('students as s','s.id','=','fs.student_id')->where('fs.student_id',$id)->get();
        $scores = DB::table('scores as s')->join('years as y','y.id','=','s.year_id')->where('s.student_id',$id)->get();
        $eduneed = DB::table('eduneeds as e')->join('students as s','s.id','=','e.student_id')->where('e.student_id',$id)->get();
        return response()->json(['student'=>$student, 'families'=>$families, 'scores'=>$scores, 'eduNeeds'=>$eduneed]);
    }

    public function store(Request $request){
        $student = new Student;
        $req = $request->all();
        if($req != NULL){
            if(!isset($req['familiesData'])) $req['familiesData'] = [];
            if(!isset($req['scoresData'])) $req['scoresData'] = [];
            if(!isset($req['eduNeedsData'])) $req['eduNeedsData'] = [];
            $validator = Validator::make($req['postData'], $student->createRules);
            if(($validator)->fails()) return response()->json(['message'=>"Validasi data salah, atau User sudah ada.", 'listError'=>$validator->messages()],422);
            
            $student->fill($req['postData']);
            $student->creator_id = Auth::User()->id; $student->updater_id = Auth::User()->id;
            $student->isActive = 1;
            $student->photo = $req['postData']['student_numb'].'.jpg';
            $student->save();
            if(Storage::disk('public')->exists('student/'.$student->photo)) 
                Storage::disk('public')->delete('student/'.$student->photo);
            Storage::disk('public')->copy('nofoto.jpg','student/'.$student->photo);
            $this->setFamiliesFromRequest($req['familiesData'],$student->id);
            $this->setScoreFromRequest($req['scoresData'],$student->id);
            $this->setEduNeedFromRequest($req['eduNeedsData'],$student->id);
            return response()->json(["message"=>"Berhasil menambah siswa","student"=>$student]);
        }
        return response()->json(["message"=>"Kesalahan server, silahkan refresh halaman dulu"],428);
    }

    public function update(Request $request, $id){
        $student = Student::findOrFail($id);
        $req = $request->all();
        if($req != NULL){
            if(!isset($req['familiesData'])) $req['familiesData'] = [];
            if(!isset($req['scoresData'])) $req['scoresData'] = [];
            if(!isset($req['eduNeedsData'])) $req['eduNeedsData'] = [];
            $validator = Validator::make($req['postData'], $student->updateRules);
            if(($validator)->fails()) return response()->json(['message'=>"Validasi data salah, atau User sudah ada.", 'listError'=>$validator->messages()],422);
            
            $student->fill($req['postData']);
            $student->updater_id = Auth::User()->id;
            $student->save();
                
            $this->setFamiliesFromRequest($req['familiesData'],$student->id);
            $this->setScoreFromRequest($req['scoresData'],$student->id);
            $this->setEduNeedFromRequest($req['eduNeedsData'],$student->id);
            return response()->json(["message"=>"Berhasil mengupdate siswa","student"=>$student]);
        }
        return response()->json(["message"=>"Kesalahan server, silahkan refresh halaman dulu"],428);
    }

    public function changeDp(Request $request, $student_numb){
        if($request->hasFile('image')){
            $this->validate($request, ["image"=>'required|image|mimes:jpeg,png,jpg,gif,svg|max:512']);
            $student = Student::where('student_numb',$student_numb)->firstOrFail();
            $imagename = $student_numb.".".$request->file('image')->getClientOriginalExtension();
            Storage::disk('public')->put('/student/'.$imagename,file_get_contents($request->file('image')));
            $student->photo = $imagename; $student->save();
            return response()->json(['message'=>"Berhasil ganti foto siswa"]);
        }
        else
            return response()->json(['message' => "tidak ada gambar"],422);
    }

    public function profile($id){
        $student = Student::with('religion')->with('education')->with('card')->where('id',$id)->firstOrFail();
        $families = DB::table('families as f')->select('f.*','r.*','o.*','e.*','fs.type')->join('family_student as fs','fs.family_id','=','f.id')->join('students as s','s.id','=','fs.student_id')->join('occupations as o','o.id','=','f.occupation_id')->join('religions as r','r.id','=','f.religion_id')->join('educations as e','e.id','=','f.education_id')->where('s.id',$id)->orderBy('fs.type','asc')->get();
        $scores = DB::table('scores as sc')->select('sc.*','y.year')->join("years as y",'y.id','=','sc.year_id')->where('sc.student_id',$id)->orderBy('y.year','asc')->get();
        $eduNeeds = DB::table('eduneeds')->where('student_id',$id)->orderBy('eduNeed','asc')->get();
        $donates = Donate::where('student_id',$id)->with('coordinator')->with('sponsor')->with('years')->orderBy('send_time','asc')->get();
        $student['families'] = $families;
        $student['scores'] = $scores;
        $student['eduNeeds'] = $eduNeeds;
        $student['donates'] = $donates;
        //return response()->json(['student'=>$student]);
        return view('content.student.profile',['student'=>$student]);
    }

    public function setFamiliesFromRequest($familiesData, $student_id){
        $i = 0;
        $family_stud = [];
        foreach ($familiesData as $family) {
            $fam = Family::updateOrCreate(['nik'=>$family['nik']], $family);
            /*
            $fam = Family::where('nik',$family['nik'])->first();
            if(!$fam){
                $fam = new Family;
                $validator = Validator::make($family, $fam->createRules);
                if(($validator)->fails()) return response()->json(['message'=>'Masalah dalam penambahan anggota keluarga ke - '.$i, 'listError'=>$validator->messages()],422);
            }
            else{
                $validator = Validator::make($family, $fam->updateRules);
                if(($validator)->fails()) return response()->json(['message'=>'Masalah dalam update anggota keluarga ke - '.$i, 'listError'=>$validator->messages()],422);
            }
            $fam->fill($family); $fam->save(); */
            $family_stud[$fam->id] = ['type' => $family['type']];
        }
        $student = Student::findOrFail($student_id);
        $student->families()->sync($family_stud);
    }

    public function setScoreFromRequest($scoresData, $student_id){
        Score::where('student_id',$student_id)->delete();
        foreach ($scoresData as $scoreObject) {
            $year = DB::table('years')->where('year',$scoreObject['year'])->first();
            $score = DB::table('scores')->where('student_id',$student_id)->where('year_id',$year->id)->get();
            if(count($score) == 0){
                $scoreObject['student_id'] = $student_id; $scoreObject['year_id']=$year->id;
                Score::create($scoreObject);
            }
            else{
                unset($scoreObject['year']);
                Score::where('student_id',$student_id)->where('year_id',$year->id)->update($scoreObject);
            }
        }
    }
    
    public function setEduneedFromRequest($eduNeedsData, $student_id){
        Eduneed::where('student_id', $student_id)->delete();
        foreach ($eduNeedsData as $eduNeedObject) {
            $eduNeedObject['student_id'] = $student_id;
            $eduNeed = DB::table('eduneeds')->where('student_id',$student_id)->where('eduNeed',$eduNeedObject['eduNeed'])->get();
            if(count($eduNeed) == 0){
                $eduNeedObject['student_id'] = $student_id;
                Eduneed::create($eduNeedObject);
            }
            else{
                Eduneed::where('student_id',$student_id)->where('eduNeed',$eduNeedObject['eduNeed'])->update($eduNeedObject);
            }
        }
    }

    public function destroy($student_numb){
        $student = Student::where('student_numb',$student_numb)->firstOrFail();
        $donates = DB::table('donates')->where('student_id',$student->id)->get();
        if(count($donates) > 0)
            return response()->json(["message"=>"Gagal hapus siswa, hapus dulu donasi yang dimiliki siswa ini"], 499);

        if(Storage::disk('public')->exists('student/'.$student->photo)) 
            Storage::disk('public')->delete('student/'.$student->photo);
        $student->delete();
        return response()->json(["message"=>"Berhasil hapus siswa"]);
    }
}