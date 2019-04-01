<!-- CPU Usage -->
@extends('dashboard')
@section('content')
    <coordinator-table inline-template>
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
                    @if(in_array(Auth::User()->level, [1,2]))
                    <div class="col-md-2">
                        <div class="input-group">
                            <button class="btn btn-success" v-on:click="openModal(false)"><i class="fa fa-plus"></i> Tambah</button>
                        </div>
                    </div>@endif
                </div>
                <div v-if="records.total > 0">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Induk</th>
                                <th>Nama</th>
                                <th>Sex</th>
                                <th>Telepon</th>
                                <th>Siswa</th>
                                <th>Kota</th>
                                <th>Status</th>
                                @if(in_array(Auth::User()->level, [1,2]))<th>Action</th>@endif
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(record,index) in records.data">
                                <td>@{{index + records.from}}</td>
                                <td>@{{record.coordinator_numb}}</td>
                                <td>@{{record.name}}</td>
                                <td>@{{record.sex}}</td>
                                <td>@{{record.contact}}</td>
                                <td><li v-for="student in record.students">
                                    @{{student.name}}
                                </li></td>
                                <td>@{{record.city}}</td>
                                <td><span v-if="record.isActive == 1">Aktif</span><span v-else>Tidak Aktif</span></td>
                                @if(in_array(Auth::User()->level, [1,2]))<td><div class="btn-group"><button class="btn btn-info" v-on:click="showRecord(record.id)" v-bind:title="'lihat ' + record.name"><i class="fa fa-eye"></i></button><button class="btn btn-warning" v-on:click="openModal(true, record)" v-bind:title="'edit ' + record.name"><i class="fa fa-pencil"></i></button><button class="btn btn-primary" v-on:click="openModalDp(record)" v-bind:title="'ganti foto ' + record.name"><i class="fa fa-file-image-o"></i></button><button class="btn btn-danger" v-on:click="deleteRecord(record)" v-bind:title="'hapus ' + record.name"><i class="fa fa-trash"></i></button></div></td>@endif
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
                <h4 class="modal-title" id="defaultModalLabel">Modif @{{recordSlot.name}}</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group">
                        <label class="col-md-2 control-label">Kode Koordinator</label>
                        <div class="col-md-7">
                            <input type="text" class="form-control" name="kode_koordinator" v-model="recordSlot.coordinator_numb" v-validate="'required|max:191'">
                            <span class="err" v-if="errors.first('kode_koordinator')">@{{ errors.first('kode_koordinator') }}</span>
                        </div>                        
                    </div> 
                    <div class="form-group">
                        <label class="col-md-2 control-label">Nama</label>
                        <div class="col-md-7">
                            <input type="text" class="form-control" name="nama" v-model="recordSlot.name" v-validate="'required|max:191'">
                            <span class="err" v-if="errors.first('nama')">@{{ errors.first('nama') }}</span>
                        </div>                        
                    </div> 
                    <div class="form-group">
                        <label class="col-md-2 control-label">Kartu ID</label>
                        <div class="col-md-7">
                            <select class="form-control" name="kartu_id" v-model="recordSlot.card_id" v-validate="'required'">
                                <option value="">-Pilih Kartu ID-</option>
                                @foreach($cards as $card)
                                <option value="{{ $card->id }}">{{ $card->card }}</option>
                                @endforeach
                            </select>
                            <span class="err" v-if="errors.first('kartu_id')">@{{ errors.first('kartu_id') }}</span>
                        </div>                        
                    </div>  
                    <div class="form-group">
                        <label class="col-md-2 control-label">Nomor ID</label>
                        <div class="col-md-7">
                            <input type="text" class="form-control" name="nomor_id" v-model="recordSlot.id_numb" v-validate="'required|max:191'">
                            <span class="err" v-if="errors.first('nomor_id')">@{{ errors.first('nomor_id') }}</span>
                        </div>                        
                    </div>   
                    <div class="form-group">
                        <label class="col-md-2 control-label">Jenis Kelamin</label>
                        <div class="col-md-7">
                            <select class="form-control" name="jenis_kelamin" v-model="recordSlot.sex" v-validate="'required'">
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                            <span class="err" v-if="errors.first('jenis_kelamin')">@{{ errors.first('jenis_kelamin') }}</span>
                        </div>                        
                    </div>  
                    <div class="form-group">
                        <label class="col-md-2 control-label">Alamat</label>
                        <div class="col-md-7">
                            <input type="text" class="form-control" name="alamat" v-model="recordSlot.address" v-validate="'max:191'">
                            <span class="err" v-if="errors.first('alamat')">@{{ errors.first('alamat') }}</span>
                        </div>                        
                    </div>      
                    <div class="form-group">
                        <label class="col-md-2 control-label">Kota</label>
                        <div class="col-md-7">
                            <input type="text" class="form-control" name="kota" v-model="recordSlot.city" v-validate="'max:191'">
                            <span class="err" v-if="errors.first('kota')">@{{ errors.first('kota') }}</span>
                        </div>                        
                    </div>  
                    <div class="form-group">
                        <label class="col-md-2 control-label">Telepon</label>
                        <div class="col-md-7">
                            <input type="text" class="form-control" name="telepon" v-model="recordSlot.contact" v-validate="'max:191'">
                            <span class="err" v-if="errors.first('telepon')">@{{ errors.first('telepon') }}</span>
                        </div>                        
                    </div>    
                    <div class="form-group">
                        <label class="col-md-2 control-label">Email</label>
                        <div class="col-md-7">
                            <input type="text" class="form-control" name="email" v-model="recordSlot.email" v-validate="'max:191|email'">
                            <span class="err" v-if="errors.first('email')">@{{ errors.first('email') }}</span>
                        </div>                        
                    </div>     
                    <div class="form-group">
                        <label class="col-md-2 control-label">Kesibukan Pekerjaan</label>
                        <div class="col-md-7">
                            <textarea class="form-control" rows="3" v-model="recordSlot.job_desc" name="deskripsi_pekerjaan" v-validate="'max:300'">@{{recordSlot.job_desc}}</textarea>
                            <span class="err" v-if="errors.first('deskripsi_pekerjaan')">@{{ errors.first('deskripsi_pekerjaan') }}</span>
                        </div>                        
                    </div>    
                    <div class="form-group">
                        <label class="col-md-2 control-label">Nama BANK</label>
                        <div class="col-md-7">
                          <input type="text" class="form-control" v-model="recordSlot.bank_name" name="nama_bank" v-validate="'max:191'">
                          <span class="err" v-if="errors.first('nama_bank')">@{{ errors.first('nama_bank') }}</span>
                        </div>                        
                      </div>                
                      <div class="form-group">
                        <label class="col-md-2 control-label">Cabang</label>
                        <div class="col-md-7">
                          <input type="text" class="form-control" v-model="recordSlot.bank_branch" name="cabang_bank" v-validate="'max:191'">
                          <span class="err" v-if="errors.first('cabang_bank')">@{{ errors.first('cabang_bank') }}</span>
                        </div>                        
                      </div>         
                      <div class="form-group">
                        <label class="col-md-2 control-label">Kota BANK</label>
                        <div class="col-md-7">
                          <input type="text" class="form-control" v-model="recordSlot.bank_city" name="kota_bank" v-validate="'max:191'">
                          <span class="err" v-if="errors.first('kota_bank')">@{{ errors.first('kota_bank') }}</span>
                        </div>                        
                      </div>                 
                      <div class="form-group">
                        <label class="col-md-2 control-label">No Rekening</label>
                        <div class="col-md-7">
                          <input type="text" class="form-control" v-model="recordSlot.account_numb" name="no_rekening" v-validate="'max:191'">
                          <span class="err" v-if="errors.first('no_rekening')">@{{ errors.first('no_rekening') }}</span>
                        </div>                        
                      </div>                
                      <div class="form-group">
                        <label class="col-md-2 control-label">Pemilik Rekening</label>
                        <div class="col-md-7">
                          <input type="text" class="form-control" v-model="recordSlot.account_name" name="nama_pemilik_rekening" v-validate="'max:191'">
                          <span class="err" v-if="errors.first('nama_pemilik_rekening')">@{{ errors.first('nama_pemilik_rekening') }}</span>
                        </div>                        
                      </div> 
                      <div class="form-group">
                        <label class="col-md-2 control-label">Catatan Khusus</label>
                        <div class="col-md-7">
                          <textarea class="form-control" rows="3" v-model="recordSlot.note" name="catatan_khusus" v-validate="'max:191'">@{{recordSlot.note}}</textarea>
                        </div>   
                        <span class="err" v-if="errors.first('catatan_khusus')">@{{ errors.first('catatan_khusus') }}</span>
                    </div>                   
                      <div class="form-group">
                        <label class="col-md-2 control-label">Status</label>
                        <div class="col-md-4">
                          <select class="form-control" v-model="recordSlot.isActive" name="status" v-validate="'required'">
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                          </select>
                          <span class="err" v-if="errors.first('status')">@{{ errors.first('status') }}</span>
                        </div>  
                      </div>  
                </div>  
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" v-on:click="closeModalTambah">Tutup</button><button type="button" class="btn btn-info" v-on:click="modifyRecord">Simpan</button>
            </div>
        </div>
    </modal>
    <modal name="modal_dp" height="auto" width="60%" :scrollable="true">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="defaultModalLabel">Ganti Gambar @{{ recordSlot.name }}</h4>
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
                <button type="button" class="btn btn-warning" v-on:click="closeModalDp">Tutup</button><button type="button" class="btn btn-success" v-on:click="changeDp" v-if="image != ''">Ganti Gambar</button>
            </div>
        </div>
    </modal>
    <modal name="modal_lihat" height="auto" width="60%" :scrollable="true">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="defaultModalLabel">@{{recordPicked.name}}</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-3">
                        <img v-bind:src="$store.state.apiUrl + 'appimages/coordinator/' + recordPicked.photo" class="img-responsive">
                    </div>
                    <div class="col-md-9">
                        <table class="table table-bordered first-fit">
                            <tr><td>Induk:</td><td>@{{ recordPicked.coordinator_numb }}</td></tr>
                            <tr><td>Nama:</td><td>@{{ recordPicked.name }}</td></tr>
                            <tr><td>Jenis Pengenal:</td><td>@{{ recordPicked.card }}</td></tr>
                            <tr><td>No. Pengenal:</td><td>@{{ recordPicked.id_numb }}</td></tr>
                            <tr><td>Jenis Kelamin:</td><td><span v-if="recordPicked.sex == 'L'">Laki-laki</span><span v-else>Perempuan</span></td></tr>
                            <tr><td>Alamat:</td><td>@{{ recordPicked.address }}</td></tr>
                            <tr><td>Kota:</td><td>@{{ recordPicked.city }}</td></tr>
                            <tr><td>Telepon:</td><td>@{{ recordPicked.contact }}</td></tr>
                            <tr><td>Email:</td><td>@{{ recordPicked.email }}</td></tr>
                            <tr><td>Pekerjaan:</td><td>@{{ recordPicked.job_desc }}</td></tr>
                            <tr><td>Akun Bank:</td><td>@{{ recordPicked.bank_name }} @{{recordPicked.bank_branch}} Kota: @{{recordPicked.bank_city}}. Atas Nama: @{{recordPicked.account_name}} - @{{ recordPicked.account_numb }}</td></tr>
                            <tr><td>Catatan Khusus:</td><td>@{{ recordPicked.note }}</td></tr>
                            <tr><td>Aktif?:</td><td><span v-if="recordPicked.isActive = 1">Aktif</span><span v-else>Tidak Aktif</span></td></tr>
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
    </coordinator-table>

            <!-- #END# CPU Usage -->
@endsection