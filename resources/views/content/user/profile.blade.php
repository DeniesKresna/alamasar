<!-- CPU Usage -->
@extends('dashboard')
@section('content')
    <user-profile-table inline-template>
  <div class="row">
  <loading v-bind:active.sync="isLoading" 
                    v-bind:can-cancel="false" 
                    v-bind:is-full-page="true">
                </loading>
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="box box-default">
            <div class="box-header with-border">
              <h3 class="box-title">@{{wizardTitle}}</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="row" style="margin-bottom: 5px">
                <div class="col-md-4"></div>
                  <div class="col-md-3">
                    <img v-bind:src="$store.state.apiUrl + 'appimages/dp/' + postData.photo + '?time=' + updatefototime" width="200" class="img-responsive">
                  </div>
                </div>
                @if(last(request()->segments()) == Auth::User()->id || Auth::User()->level == 1)
              <div class="row" style="margin-bottom: 10px">
                <div class="col-md-4"></div>
                <button class="btn btn-info btn-sm" v-on:click="openModalDp()" v-bind:title="'ganti foto ' + postData.name"><i class="fa fa-file-image-o"></i> Ganti Foto</button>
              </div>
              @endif
              <div class="form form-horizontal">
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Nama</label>
                                    <div class="col-md-9">
                                      <input type="text" class="form-control" v-model="postData.name" name="name" v-validate="'required|max:191'">
                                      <span class="err" v-if="errors.first('nama')">@{{ errors.first('nama') }}</span>
                                    </div>                        
                                  </div>
                                  <div class="form-group">
                                    <label class="col-md-2 control-label">Email</label>
                                    <div class="col-md-9">
                                      <input type="email" class="form-control" v-model="postData.email" name="email" v-validate="'email|required|max:191'" readonly>
                                      <span class="err" v-if="errors.first('email')">@{{ errors.first('email') }}</span>
                                    </div>                        
                                  </div>
                                  <div class="form-group">
                                    <label class="col-md-2 control-label">Jenis Kelamin</label>
                                    <div class="col-md-9">
                                      <select class="form-control" v-model="postData.sex" name="jenis_kelamin" v-validate="'required'">
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                      </select>
                                      <span class="err" v-if="errors.first('jenis_kelamin')">@{{ errors.first('jenis_kelamin') }}</span>
                                    </div>                   
                                  </div> 
                                  <div class="form-group">
                                    <label class="col-md-2 control-label">Telepon</label>
                                    <div class="col-md-9">
                                      <input type="text" class="form-control" v-model="postData.contact" name="telepon" v-validate="'max:191'">
                                      <span class="err" v-if="errors.first('telepon')">@{{ errors.first('telepon') }}</span>
                                    </div>                        
                                  </div>
                                  <div class="form-group">
                                    <label class="col-md-2 control-label">Level</label>
                                    <div class="col-md-9">
                                      <select class="form-control" v-model="postData.level" name="level" v-validate="'required'">
                                        @if(Auth::User()->level == 1) <option value="1">Administrator</option> @endif
                                        <option value="2">Office</option>
                                        <option value="3">User Biasa</option>
                                      </select>
                                      <span class="err" v-if="errors.first('level')">@{{ errors.first('level') }}</span>
                                    </div>                   
                                  </div> 
                                  @if(last(request()->segments()) == Auth::User()->id || Auth::User()->level == 1)
                                  <div class="form-group">
                                    <div class="col-md-11"><button type="button" class="btn btn-success pull-right" v-on:click="modifyRecord">Simpan</button></div>
                                  </div>
                                  @endif
                          </div>
            </div>            
            <div class="box-footer">
             
            </div>
          </div>
          <!-- /.box -->
    </div>
@if(last(request()->segments()) == Auth::User()->id)
<div class="col-md-12 col-sm-12 col-xs-12">
        <div class="box box-default">
            <div class="box-header with-border">
              <h3 class="box-title">Ganti Password</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="form form-horizontal">
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Password Lama</label>
                                    <div class="col-md-9">
                                      <input type="password" class="form-control" v-model="postPassword.old_password">
                                    </div>                        
                                  </div>
                                  <div class="form-group">
                                    <label class="col-md-2 control-label">Password Baru</label>
                                    <div class="col-md-9">
                                      <input type="password" class="form-control" v-model="postPassword.new_password" >
                                    </div>                        
                                  </div>
                                  <div class="form-group">
                                    <label class="col-md-2 control-label">Konfirmasi</label>
                                    <div class="col-md-9">
                                      <input type="password" class="form-control" v-model="postPassword.new_password2">
                                    </div>                        
                                  </div>
                                  <div class="form-group">
                                    <div class="col-md-11"><button type="button" class="btn btn-success pull-right" v-on:click="modifyPassword">Simpan</button></div>
                                  </div>
                          </div>
            </div>            
            <div class="box-footer">
             
            </div>
          </div>
          <!-- /.box -->
    </div>
    @endif
    <modal name="modal_dp" height="auto" width="60%" :scrollable="true">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="defaultModalLabel">Ganti Gambar @{{ postData.name }}</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group">
                        <label class="col-md-2 control-label">Ganti Gambar</label>
                        <div class="col-md-7">
                            <input type="file" class="form-control" accept="image/*" name="gambar_dp" v-validate="'required|image|size:512'" v-on:change="getDp">
                            <span class="err" v-if="errors.first('gambar_dp')">@{{ errors.first('gambar_dp') }}</span>
                        </div>                        
                    </div>               
                </div>  
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" v-on:click="closeModal">Tutup</button><button type="button" class="btn btn-success" v-on:click="changeDp" v-if="image != ''">Ganti Gambar</button>
            </div>
        </div>
    </modal>
</div>
    </user-profile-table>

            <!-- #END# CPU Usage -->
@endsection