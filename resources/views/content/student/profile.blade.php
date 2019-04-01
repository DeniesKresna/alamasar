@extends('dashboard')
@section('content')
<div class="row">
        <div class="col-md-3">

          <!-- Profile Image -->
          <div class="box box-primary">
            <div class="box-body box-profile">
              <img class="profile-user-img img-responsive img-circle" src="{{ asset('appimages/student/'.$student->photo) }}" alt="User profile picture">

              <h3 class="profile-username text-center">{{ $student->name }}</h3>

              <p class="text-muted text-center">ID Code - {{ $student->student_numb }}</p>
              <p class="text-muted text-center">Status - @if($student->status == 'current') Current @elseif($student->status=='lulus') Lulus @elseif($student->status=='dropout') Drop Out @endif</p>
              @if($student->status_desc != '')<p class="text-muted text-center">{{$student->status_desc}}</p>@endif
              <!--
              <ul class="list-group list-group-unbordered">
                <li class="list-group-item">
                  <b>Followers</b> <a class="pull-right">1,322</a>
                </li>
                <li class="list-group-item">
                  <b>Following</b> <a class="pull-right">543</a>
                </li>
                <li class="list-group-item">
                  <b>Friends</b> <a class="pull-right">13,287</a>
                </li>
              </ul>
				-->
              <a href="{{route('student.edit',['id'=>$student->id])}}" class="btn btn-primary btn-block"><b>Edit Siswa</b></a>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

          <!-- About Me Box -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Tentang Siswa</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <strong><i class="fa fa-book margin-r-5"></i> Pendidikan Terakhir</strong>

              <p class="text-muted">
                Gelar : {{ $student->education->education }}
              </p>
              <p class="text-muted">
              	{{ $student->education_desc }}
              </p>

              <hr>

              <strong><i class="fa fa-map-marker margin-r-5"></i> Alamat Siswa</strong>

              <p class="text-muted">{{ $student->domicile }}</p>

              <hr>

              <strong><i class="fa fa-phone margin-r-5"></i> Telepon</strong>

              <p class="text-muted">{{ $student->contact }}</p>

              <hr>

              <strong><i class="fa fa-envelope margin-r-5"></i> Email</strong>

              <p class="text-muted">{{ $student->email }}</p>

              <hr>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#activity" data-toggle="tab">Biodata Siswa</a></li>
              <li><a href="#timeline" data-toggle="tab">Keluarga</a></li>
              <li><a href="#settings" data-toggle="tab">Pendidikan</a></li>
              <li><a href="#donates" data-toggle="tab">Donasi</a></li>
            </ul>
            <div class="tab-content">
              <div class="active tab-pane" id="activity">
                <form class="form-horizontal">
                  <div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label">Kartu ID</label>

                    <div class="col-sm-10">
                      <input type="text" class="form-control" readonly placeholder="-" value="{{ $student->card->card }} - {{ $student->id_numb }}">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail" class="col-sm-2 control-label">Jenis Kelamin</label>

                    <div class="col-sm-10">
                      <input type="text" class="form-control" readonly placeholder="-" value="@if($student->sex == 'L') Laki-laki @else Perempuan @endif">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label">Alamat Sesuai Kartu ID</label>

                    <div class="col-sm-10">
                      <textarea class="form-control" readonly placeholder="-" rows="3">{{ $student->address }}</textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputExperience" class="col-sm-2 control-label">Tempat, Tanggal Lahir</label>

                    <div class="col-sm-10">
                      <input type="text" class="form-control" readonly placeholder="-" value="{{ $student->birth_place }}, @php echo date('d-m-Y',strtotime($student->birth_date)); @endphp">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputSkills" class="col-sm-2 control-label">Agama</label>

                    <div class="col-sm-10">
                      <input type="text" class="form-control" readonly placeholder="-" value="{{ $student->religion->religion }}">
                    </div>
                  </div>           
                  <div class="form-group">
                    <label for="inputSkills" class="col-sm-2 control-label">BANK</label>

                    <div class="col-sm-10">
                      <textarea type="text" class="form-control" readonly placeholder="-">{{ $student->bank_name }} Cabang {{ $student->bank_branch }}, {{ $student->bank_city }}. No. Rek: {{ $student->account_numb }}</textarea>
                    </div>
                  </div>         
                  <div class="form-group">
                    <label for="inputSkills" class="col-sm-2 control-label">Catatan Khusus</label>

                    <div class="col-sm-10">
                      <textarea type="text" class="form-control" readonly placeholder="-">{{ $student->note }}</textarea>
                    </div>
                  </div>
                </form>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="timeline">
              	<div class="text-center"><h3> Anggota Keluarga</h3></div>
              	<div class="text-center"><small>No KK. {{ $student->cc_numb }}</small></div>
                 <form class="form-horizontal">
                  <div class="row">
                  	@foreach($student->families as $family)
                  	<div class="col-sm-6">
						          <span class="text-center"><h4>{{ $family->type }}</h4></span>                  			
                  	  <div class="row">
  		                  <div class="form-group">
  		                    <label for="inputName" class="control-label col-sm-3">NIK</label>
  		                    <div class="col-sm-8">
  		                      <input type="text" class="form-control" readonly placeholder="-" value="{{ $family->nik }}">
  		                    </div>
  		                  </div>
		                  </div>
                      <div class="row">
  		                  <div class="form-group">
  		                    <label for="inputName" class="control-label col-sm-3">Nama</label>
  		                    <div class="col-sm-8">
  		                      <input type="text" class="form-control" readonly placeholder="-" value="{{ $family->name }}">
  		                    </div>
  		                  </div>
		                  </div>
                      <div class="row">
  		                  <div class="form-group">
  		                    <label for="inputName" class="control-label col-sm-3">Alamat</label>
  		                    <div class="col-sm-8">
  		                      <textarea rows="3" class="form-control" readonly placeholder="-">{{ $family->address }}</textarea>
  		                    </div>
  		                  </div>
		                  </div>
                      <div class="row">
  		                  <div class="form-group">
  		                    <label for="inputName" class="control-label col-sm-3">Tempat Lahir</label>
  		                    <div class="col-sm-8">
  		                      <input type="text" class="form-control" readonly placeholder="-" value="{{ $family->birth_place }}">
  		                    </div>
  		                  </div>
		                  </div>
                      <div class="row">
  		                  <div class="form-group">
  		                    <label for="inputName" class="control-label col-sm-3">Tanggal Lahir</label>
  		                    <div class="col-sm-8">
  		                      <input type="text" class="form-control" readonly placeholder="-" value="@php echo date('d-m-Y', strtotime($family->birth_date)) @endphp">
  		                    </div>
  		                  </div>
		                  </div>
                      <div class="row">
  		                  <div class="form-group">
  		                    <label for="inputName" class="control-label col-sm-3">Telepon</label>
  		                    <div class="col-sm-8">
  		                      <input type="text" class="form-control" readonly placeholder="-" value="{{ $family->contact }}">
  		                    </div>
  		                  </div>
		                  </div>
                      <div class="row">
  		                  <div class="form-group">
  		                    <label for="inputName" class="control-label col-sm-3">Agama</label>
  		                    <div class="col-sm-8">
  		                      <input type="text" class="form-control" readonly placeholder="-" value="{{ $family->religion }}">
  		                    </div>
  		                  </div>
  		                </div>
                      <div class="row">
  		                  <div class="form-group">
  		                    <label for="inputName" class="control-label col-sm-3">Pekerjaan</label>
  		                    <div class="col-sm-8">
  		                      <input type="text" class="form-control" readonly placeholder="-" value="{{ $family->occupation }}">
  		                    </div>
  		                  </div>
		                  </div>
                      <div class="row">
  		                  <div class="form-group">
  		                    <label for="inputName" class="control-label col-sm-3">Pendidikan</label>
  		                    <div class="col-sm-8">
  		                      <input type="text" class="form-control" readonly placeholder="-" value="{{ $family->education }}">
  		                    </div>
  		                  </div>
		                  </div>
                      <div class="row">
  		                  <div class="form-group">
  		                    <label for="inputName" class="control-label col-sm-3">Status</label>
  		                    <div class="col-sm-8">
  		                      <input type="text" class="form-control" readonly placeholder="-" value="{{ $family->marital_status }}">
  		                    </div>
  		                  </div>
		                  </div>
                      <div class="row">
  		                  <div class="form-group">
  		                    <label for="inputName" class="control-label col-sm-3">Meninggal?</label>
  		                    <div class="col-sm-8">
  		                      <input type="text" class="form-control" readonly placeholder="-" value="@if($family->isDied > 0) Meninggal @else Masih Hidup @endif">
  		                    </div>
  		                  </div>
	                  </div>
	                </div>
	                @endforeach
                  </div>
                 </form>
              </div>
              <!-- /.tab-pane -->

              <div class="tab-pane" id="settings">
                <h3> Daftar Nilai Siswa</h3>
                  @if(count($student->scores)>0)
                        @php $i=0; @endphp
                         <table class="table table-bordered">
                            <thead>
                                <tr>
                                  <th>No</th>
                                  <th>Tahun Ajaran</th>
                                  <th>Jenis</th>
                                  <th>Nilai</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($student->scores as $score)
                                @php $i++; @endphp
                                <tr>
                                    <td>{{ $i }}</td>
                                    <td>{{ $score->year }}</td>
                                    <td>@if($score->type == 'nb') Nilai Biasa @else Indeks Prestasi @endif</td>
                                    <td>{{ $score->score }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                         </table>
                        @else
                          Belum ada data nilai
                        @endif
                        <hr>
                        <h3> Daftar Kebutuhan Pendidikan</h3>
                       @if(count($student->eduNeeds)>0)
                        @php $i=0; $total=0; @endphp
                         <table class="table table-bordered">
                            <thead>
                                <tr>
                                  <th>No</th>
                                  <th>Kebutuhan</th>
                                  <th>Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($student->eduNeeds as $eduNeed)
                                @php $i++; $total = $total + $eduNeed->price @endphp
                                <tr>
                                    <td>{{ $i }}</td>
                                    <td>{{ $eduNeed->eduNeed }}</td>
                                    <td>Rp. {{ $eduNeed->price }} ,-</td>
                                </tr>
                                @endforeach
                                <tr>
                                  <td colspan="2" class="text-center">Total Kebutuhan</td>
                                  <td>Rp. {{ $total }} ,-</td>
                                </tr>
                            </tbody>
                         </table>
                        @else
                          Belum ada data kebutuhan pendidikan
                        @endif
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="donates">
                <h3> History Donasi</h3>
                  @if(count($student->donates)>0)
                        @php $i=0; @endphp
                         <table class="table table-bordered">
                            <thead>
                                <tr>
                                  <th>No</th>
                                  <th>Donatur</th>
                                  <th>Koordinator</th>
                                  <th>Tahun Ajaran</th>
                                  <th>Nominal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($student->donates as $donate)
                                @php $i++; @endphp
                                <tr>
                                    <td>{{ $i }}</td>
                                    <td>{{ $donate->sponsor->name }}</td>
                                    <td>{{ $donate->coordinator->name }}</td>
                                    <td><ul>@foreach($donate->years as $year)
                                            <li>{{$year->year}}</li>
                                        @endforeach
                                        </ul>
                                    </td>
                                    <td>Rp. {{$donate->amount}} ,-</td>
                                </tr>
                                @endforeach
                            </tbody>
                         </table>
                        @else
                          Belum ada data nilai
                        @endif
              </div>
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
      </div>

@endsection('content')