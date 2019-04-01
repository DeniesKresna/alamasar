<!-- CPU Usage -->
@extends('dashboard')
@section('content')
    <donate-table inline-template>
  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="box box-default">
            <div class="box-header with-border">
              <h3 class="box-title">Daftar @{{tableDescription}}</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <!-- /.box-body -->
                <loading v-bind:active.sync="isLoading" 
                    v-bind:can-cancel="false" 
                    v-bind:is-full-page="true">
                </loading>
                <div class="row">
                    <div class="col-md-8">
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-search"></i>
                            </span>
                            <div class="form-line">
                                <input type="text" class="form-control" placeholder="Tekan enter setelah menulis.." v-model="search" v-on:keyup.enter="getRecords">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="input-group">
                            <button class="btn btn-default" v-on:click="resetGetRecords"><i class="fa fa-refresh"></i> Reset</button>
                        </div>
                    </div>
                    @if(in_array(Auth::User()->level, [1,2]))
                    <div class="col-md-3">
                        <span class="input-group">
                            <button class="btn btn-success" v-on:click="openModal(false)"><i class="fa fa-plus"></i> Tambah</button>&nbsp
                            <a target="_blank" class="btn btn-info" href="{{asset('donate_export')}}"><i class="fa fa-file-excel-o"></i> Export</a>
                        </span>
                    </div>@endif
                </div>
                <div v-if="records.total > 0">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Koordinator</th>
                                <th>Sponsor</th>
                                <th>Siswa</th>
                                <th>Tahun Ajaran</th>
                                <th>Jumlah</th>
                                @if(in_array(Auth::User()->level, [1,2]))<th>Action</th>@endif
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(record,index) in records.data">
                                <td>@{{index + records.from}}</td>
                                <td>@{{record.coordinator.name}}</td>
                                <td>@{{record.sponsor.name}}</td>
                                <td><a v-bind:href="$store.state.apiUrl + 'student/profile/' + record.student.id">@{{record.student.name}}</a></td>
                                <td><ul><li v-for="year in record.years">@{{year.year}}</li></ul></td>
                                <td>Rp. @{{record.amount}} ,-</td>
                                @if(in_array(Auth::User()->level, [1,2]))<td><div class="btn-group"><button class="btn btn-info" v-on:click="showRecord(record.donate_id)" v-bind:title="'lihat donasi'"><i class="fa fa-eye"></i></button><button class="btn btn-success" v-on:click="openModalDp(record)" v-bind:title="'update nota'"><i class="fa fa-file-image-o"></i></button><button class="btn btn-danger" v-on:click="deleteDonate(record)" v-bind:title="'hapus ' + record.name"><i class="fa fa-trash"></i></button></div></td>@endif
                            </tr>
                        </tbody>
                    </table>
                    <pagination v-bind:data="records" v-on:pagination-change-page="getRecords" v-if="!isLoading"></pagination>
                </div>
                <div v-else>
                    <p>Tidak ada data</p>
                </div>
            </div>            
            <div class="box-footer">
             
            </div>
          </div>
          <!-- /.box -->
    </div>
    <form class="form form-horizontal">
    <modal name="modal_tambah" height="auto" width="60%" :scrollable="true">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="defaultModalLabel">Tambah Donasi, nota tidak wajib diisi</h4>
            </div>
            <div class="modal-body">
                    <div class="form-group">
                        <label class="col-md-2">Siswa</label>
                        <div class="col-md-10">
                            <multiselect v-model="recordSlot.student" v-bind:options="students" placeholder="Select one" label="name" v-bind:custom-label="nameWithNumbStud"></multiselect>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2">Koordinator</label>
                        <div class="col-md-10">
                            <multiselect v-model="recordSlot.coordinator" v-bind:options="coordinators" placeholder="Select one" label="name" v-bind:custom-label="nameWithNumbCoor"></multiselect>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2">Sponsor</label>
                        <div class="col-md-10">
                            <multiselect v-model="recordSlot.sponsor" v-bind:options="sponsors" placeholder="Select one" label="name" v-bind:custom-label="nameWithNumbSpons"></multiselect>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2">Tahun Ajaran</label>
                        <div class="col-md-10">
                        <multiselect v-model="recordSlot.years" v-bind:options="years" v-bind:multiple="true" placeholder="Select Year" label="year" track-by="id"></multiselect>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2">Dana</label>
                        <div class="col-md-10">
                        <input type="text" class="form-control" name="dana" v-model="recordSlot.amount" placeholder="Jumlah Dana">
                        </div>
                        <span class="err" v-if="errors.first('dana')">@{{ errors.first('dana') }}</span>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2">Tgl Kirim Dana</label>
                        <div class="col-md-10">
                        <input type="date" class="form-control" name="tanggal_pengiriman" v-model="recordSlot.send_time" placeholder="Tanggal Pengiriman" v-validate="'required'">
                        </div>
                        <span class="err" v-if="errors.first('tanggal_pengiriman')">@{{ errors.first('tanggal_pengiriman') }}</span>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2">Nota</label>
                        <div class="col-md-10">
                        <input type="file" class="form-control" name="nota" placeholder="Gambar Nota" v-validate="'image|size:512'" v-on:change="getNota">
                        </div>
                        <span class="err" v-if="errors.first('nota')">@{{ errors.first('nota') }}</span>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" v-on:click="closeModalTambah">Tutup</button><button type="button" class="btn btn-info" v-on:click="modifyDonate">Simpan</button>
            </div>
        </div>
    </modal>
    <modal name="modal_dp" height="auto" width="60%" :scrollable="true">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="defaultModalLabel">Update Nota Donasi ini</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group">
                        <label class="col-md-2 control-label">Update Nota</label>
                        <div class="col-md-7">
                            <input type="file" class="form-control" accept="image/*" name="gambar_nota" v-validate="'required|image|size:512'" v-on:change="getNota">
                            <span class="err" v-if="errors.first('gambar_dp')">@{{ errors.first('gambar_nota') }}</span>
                        </div>                        
                    </div>               
                </div>  
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" v-on:click="closeModal">Tutup</button><button type="button" class="btn btn-success" v-on:click="changeDp" v-if="image != ''">Ganti Gambar</button>
            </div>
        </div>
    </modal>
    <modal name="modal_lihat" height="auto" width="60%" :scrollable="true">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="defaultModalLabel">Donasi untuk siswa <strong>@{{recordPicked.student.name}}</strong></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-3">
                        <img v-bind:src="$store.state.apiUrl + 'appimages/donate/' + recordPicked.photo" class="img-responsive">
                    </div>
                    <div class="col-md-9">
                        <table class="table table-bordered first-fit">
                            <tr><td>Koordinator:</td><td>@{{ recordPicked.coordinator.name }}</td></tr>
                            <tr><td>Sponsor:</td><td>@{{ recordPicked.sponsor.name }}</td></tr>
                            <tr><td>Tahun Ajaran:</td><td><ul><li v-for="year in recordPicked.years">@{{year.year}}</li></ul></td></tr>
                            <tr><td>Jumlah:</td><td>@{{ recordPicked.amount }}</td></tr>
                            <tr><td>Waktu:</td><td>@{{ recordPicked.send_time | toDMYHIS }}</td></tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" v-on:click="closeModalLihat">Tutup</button>
            </div>
        </div>
    </modal>
    </form>
</div>
    </donate-table>

            <!-- #END# CPU Usage -->
@endsection