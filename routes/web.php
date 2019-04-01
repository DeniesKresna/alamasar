<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('home');
});
Route::get('/auth', 'AuthController@index')->name('auth.page');

Route::post('/auth', 'AuthController@attempt')->name('auth.login');
Route::get('/auth/logout','AuthController@logout')->name('auth.logout');


Route::group(['middleware' => ['web','officer']], function(){
	Route::get('coordinators_sponsors','AppController@coordinatorsSponsors');
	Route::get('coordinators_sponsors_students','AppController@coordinatorsSponsorsStudents');


	Route::get('student/add/page', 'StudentController@add')->name('student.add'); //halaman tambah
	Route::get('student/add/page/{id}', 'StudentController@add')->name('student.edit'); //halaman edit
	Route::post('student', 'StudentController@store'); //tambah data
	Route::post('student/dp/{student_numb}','StudentController@changeDp');
	Route::patch('student/{id}', 'StudentController@update'); //update siswa
	Route::delete('student/{student_numb}', 'StudentController@destroy'); //update siswa

	//======================== user =================================
	Route::post('user', 'UserController@store');
	Route::patch('user/aktif/{id}', 'UserController@toogleaktif');	
	Route::delete('user/{id}', 'UserController@destroy');	
	//======================== user =================================

	//======================== Family =================================
	Route::post('family', 'FamilyController@store');
	Route::patch('family/{id}', 'FamilyController@update');
	Route::patch('family/aktif/{id}', 'FamilyController@toogleaktif');
	
	//======================== Family =================================


	//======================== sponsor =================================
	Route::post('sponsor', 'SponsorController@store');
	Route::patch('sponsor/{id}', 'SponsorController@update');
	Route::patch('sponsor/aktif/{id}', 'SponsorController@toogleaktif');
	Route::post('sponsor/dp/{sponsor_numb}','SponsorController@changeDp');
	Route::delete('sponsor/{id}','SponsorController@destroy');
	
	//======================== sponsor =================================

	//======================== coordinator =================================
	Route::post('coordinator', 'CoordinatorController@store');
	Route::patch('coordinator/{id}', 'CoordinatorController@update');
	Route::patch('coordinator/aktif/{id}', 'CoordinatorController@toogleaktif');
	Route::post('coordinator/dp/{coordinator_numb}','CoordinatorController@changeDp');
	Route::delete('coordinator/{id}','CoordinatorController@destroy');
	
	//======================== coordinator =================================

	//======================== donate =================================
	Route::post('donate', 'DonateController@store');
	Route::get('donate_export','DonateController@export');
	Route::patch('donate/{id}', 'DonateController@update');
	Route::delete('donate/{id}', 'DonateController@destroy');
	Route::patch('donate/aktif/{id}', 'DonateController@toogleaktif');
	Route::post('donate/dp/{id}','DonateController@changeDp');
	Route::post('donate/{id}','DonateController@destroy');
	
	//======================== donate =================================

	//======================== religion =================================
	Route::post('religion', 'ReligionController@store');
	Route::patch('religion/{id}', 'ReligionController@update');
	Route::delete('religion/{id}', 'ReligionController@destroy');
	Route::patch('religion/aktif/{id}', 'ReligionController@toogleaktif');
	
	//======================== religion =================================

	//======================== nation =================================
	Route::post('nation', 'NationController@store');
	Route::patch('nation/{id}', 'NationController@update');
	Route::delete('nation/{id}', 'NationController@destroy');
	Route::patch('nation/aktif/{id}', 'NationController@toogleaktif');
	
	//======================== nation =================================

	//======================== occupation =================================
	Route::post('occupation', 'OccupationController@store');
	Route::patch('occupation/{id}', 'OccupationController@update');
	Route::delete('occupation/{id}', 'OccupationController@destroy');
	Route::patch('occupation/aktif/{id}', 'OccupationController@toogleaktif');
	
	//======================== occupation =================================

	//======================== education =================================
	Route::post('education', 'EducationController@store');
	Route::patch('education/{id}', 'EducationController@update');
	Route::delete('education/{id}', 'EducationController@destroy');
	Route::patch('education/aktif/{id}', 'EducationController@toogleaktif');
	
	//======================== education =================================

	//======================== card =================================
	Route::post('card', 'CardController@store');
	Route::patch('card/{id}', 'CardController@update');
	Route::delete('card/{id}', 'CardController@destroy');
	Route::patch('card/aktif/{id}', 'CardController@toogleaktif');
	
	//======================== card =================================
	
	//======================== year =================================
	Route::post('year', 'YearController@store');
	Route::patch('year/{id}', 'YearController@update');
	Route::delete('year/{id}', 'YearController@destroy');
	Route::patch('year/aktif/{id}', 'YearController@toogleaktif');
	
	//======================== year =================================

});
Route::group(['middleware' => ['web','biasa']], function(){
	Route::get('/home', function(){
		return view('content.authed');
	})->name('home');

	//======================== student =================================
	Route::get('student/page', 'StudentController@index')->name('student.page'); //halaman lihat
	Route::get('student', 'StudentController@data'); //data inisial tabel
	Route::get('student/show/{id}', 'StudentController@show');
	Route::get('student/profile/{id}','StudentController@profile');
	Route::get('student/{id}', 'StudentController@show'); // lihat per siswa
	Route::get('student_family/{id}', 'StudentController@studentFamily'); //lihat biodata siswa + family
	//======================== student =================================

	//======================== Family =================================
	Route::get('family/page', 'FamilyController@index')->name('family.page');
	Route::get('family', 'FamilyController@data');
	Route::get('family/{id}', 'FamilyController@show');
	Route::get('family/nik/{nik}', 'FamilyController@showByNik');
	
	//======================== Family =================================

	//======================== sponsor =================================
	Route::get('sponsor/page', 'SponsorController@index')->name('sponsor.page');
	Route::get('sponsor', 'SponsorController@data');
	Route::get('sponsor/{id}', 'SponsorController@show');	
	//======================== sponsor =================================

	//======================== coordinator =================================
	Route::get('coordinator/page', 'CoordinatorController@index')->name('coordinator.page');
	Route::get('coordinator', 'CoordinatorController@data');
	Route::get('coordinator/{id}', 'CoordinatorController@show');
	
	//======================== coordinator =================================

	//======================== donate =================================
	Route::get('donate/page', 'DonateController@index')->name('donate.page');
	Route::get('donate', 'DonateController@data');
	Route::get('donate/student/{id}', 'DonateController@student');
	Route::get('donate/{id}', 'DonateController@show');
	
	//======================== donate =================================

	//======================== religion =================================
	Route::get('religion/page', 'ReligionController@index')->name('religion.page');
	Route::get('religion', 'ReligionController@data');
	Route::get('religion/{id}', 'ReligionController@show');
	
	//======================== religion =================================

	//======================== nation =================================
	Route::get('nation/page', 'NationController@index')->name('nation.page');
	Route::get('nation', 'NationController@data');
	Route::get('nation/{id}', 'NationController@show');
	
	//======================== nation =================================

	//======================== occupation =================================
	Route::get('occupation/page', 'OccupationController@index')->name('occupation.page');
	Route::get('occupation', 'OccupationController@data');
	Route::get('occupation/{id}', 'OccupationController@show');
	
	//======================== occupation =================================

	//======================== education =================================
	Route::get('education/page', 'EducationController@index')->name('education.page');
	Route::get('education', 'EducationController@data');
	Route::get('education/{id}', 'EducationController@show');
	
	//======================== education =================================

	//======================== card =================================
	Route::get('card/page', 'CardController@index')->name('card.page');
	Route::get('card', 'CardController@data');
	Route::get('card/{id}', 'CardController@show');
	
	//======================== card =================================
	
	//======================== year =================================
	Route::get('year/page', 'YearController@index')->name('year.page');
	Route::get('year', 'YearController@data');
	Route::get('year/list', 'YearController@list');
	Route::get('year/{id}', 'YearController@show');
	
	//======================== year =================================

	//======================== user =================================
	Route::get('user/page', 'UserController@index')->name('user.page');
	Route::get('user', 'UserController@data');
	Route::get('user/{id}', 'UserController@show');
	Route::get('user/profile/{id}', 'UserController@profile')->name('user.profile');
	Route::get('user/profiledata/{id}', 'UserController@profiledata');
	Route::post('user/password','UserController@changePassword');
	Route::patch('user/{id}', 'UserController@update');
	Route::post('user/dp/{id}','UserController@changeDp');
	//======================== user =================================
});
Route::group(['middleware' => ['web','admin']], function(){


	
});

	Route::get('app/error','AppController@errorShow')->name('error.show');