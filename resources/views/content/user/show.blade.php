<!-- CPU Usage -->
@extends('dashboard')
@section('content')
    <user-table inline-template>
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
                    <div class="col-md-9">
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
                    <div class="col-md-2">
                        <div class="input-group">
                            <button class="btn btn-success" v-on:click="openModal(false)"><i class="fa fa-plus"></i> Tambah</button>
                        </div>
                    </div>
                </div>
                <div v-if="records.total > 0">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>L/P</th>
                                <th>Telepon</th>
                                <th>Role</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(record,index) in records.data">
                                <td>@{{index + records.from}}</td>
                                <td>@{{record.name}}</td>
                                <td>@{{record.sex}}</td>
                                <td>@{{record.contact}}</td>
                                <td>@{{record.level | role}}</td>
                                <td><div class="btn-group"><a v-bind:href="$store.state.apiUrl + 'user/profile/' + record.id" class="btn btn-info" v-bind:title="'lihat ' + record.name"><i class="fa fa-eye"></i></a><button class="btn btn-danger" v-on:click="deleteRecord(record)" v-bind:title="'hapus ' + record.card"><i class="fa fa-trash"></i></button></div></td>
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
                <modal name="modal_tambah" height="auto" width="60%" :scrollable="true">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="defaultModalLabel">Tambah User</h4>
                        </div>
                        <form v-on:submit.prevent="modifyRecord">
                            <div class="modal-body">
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Nama</label>
                                    <div class="col-md-10">
                                      <input type="text" class="form-control" v-model="recordSlot.name" name="name" v-validate="'required|max:191'">
                                      <span class="err" v-if="errors.first('nama')">@{{ errors.first('nama') }}</span>
                                    </div>                        
                                  </div>
                                  <div class="form-group">
                                    <label class="col-md-2 control-label">Email</label>
                                    <div class="col-md-10">
                                      <input type="email" class="form-control" v-model="recordSlot.email" name="email" v-validate="'email|required|max:191'">
                                      <span class="err" v-if="errors.first('email')">@{{ errors.first('email') }}</span>
                                    </div>                        
                                  </div>
                                  <div class="form-group">
                                    <label class="col-md-2 control-label">Jenis Kelamin</label>
                                    <div class="col-md-10">
                                      <select class="form-control" v-model="recordSlot.sex" name="jenis_kelamin" v-validate="'required'">
                                        <option value="L">Laki-laki</option>
                                        <option value="P" disabled>Perempuan</option>
                                      </select>
                                      <span class="err" v-if="errors.first('jenis_kelamin')">@{{ errors.first('jenis_kelamin') }}</span>
                                    </div>                   
                                  </div> 
                                  <div class="form-group">
                                    <label class="col-md-2 control-label">Telepon</label>
                                    <div class="col-md-10">
                                      <input type="text" class="form-control" v-model="recordSlot.contact" name="telepon" v-validate="'max:191'">
                                      <span class="err" v-if="errors.first('telepon')">@{{ errors.first('telepon') }}</span>
                                    </div>                        
                                  </div>
                                  <div class="form-group">
                                    <label class="col-md-2 control-label">Level</label>
                                    <div class="col-md-10">
                                      <select class="form-control" v-model="recordSlot.level" name="level" v-validate="'required'">
                                        @if(Auth::User()->level == 1) <option value="1">Administrator</option> @endif
                                        <option value="2">Office</option>
                                        <option value="3">User Biasa</option>
                                      </select>
                                      <span class="err" v-if="errors.first('level')">@{{ errors.first('level') }}</span>
                                    </div>                   
                                  </div> 
                            </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-info" v-on:click="closeModal">Tutup</button><button type="button" class="btn btn-success" v-on:click="modifyRecord">Simpan</button>
                            </div>
                        </form>
                    </div>
                </modal>
            </div>
    </user-table>

            <!-- #END# CPU Usage -->
@endsection