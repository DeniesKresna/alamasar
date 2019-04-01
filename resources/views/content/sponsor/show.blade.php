<!-- CPU Usage -->
@extends('dashboard')
@section('content')
    <sponsor-table inline-template>
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
                    </div>@if(in_array(Auth::User()->level, [1,2]))
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
                                <th>Nama/Lembaga</th>
                                <th>Telepon</th>
                                <th>Total Donasi</th>
                                @if(in_array(Auth::User()->level, [1,2]))<th>Action</th>@endif
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(record,index) in records.data">
                                <td>@{{index + records.from}}</td>
                                <td>@{{record.sponsor_numb}}</td>
                                <td>@{{record.name}}</td>
                                <td>@{{record.contact}}</td>
                                <td>Rp. @{{record.amount_total}} ,-</td>
                                @if(in_array(Auth::User()->level, [1,2]))<td><div class="btn-group"><button class="btn btn-info" v-on:click="showRecord(record.id)" v-bind:title="'lihat ' + record.name"><i class="fa fa-eye"></i></button><button class="btn btn-warning" v-on:click="openModal(true, record)" v-bind:title="'edit ' + record.name"><i class="fa fa-pencil"></i></button><button class="btn btn-primary" v-on:click="openModalDp(record)" v-bind:title="'ganti foto ' + record.name"><i class="fa fa-file-image-o"></i></button><button class="btn btn-danger" v-on:click="deleteRecord(record)" v-bind:title="'hapus ' + record.name"><i class="fa fa-trash"></i></button></div></div></td>@endif
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
                        <label class="col-md-2 control-label">Kode Sponsor</label>
                        <div class="col-md-7">
                            <input type="text" class="form-control" name="kode_sponsor" v-model="recordSlot.sponsor_numb" v-validate="'required|max:191'">
                            <span class="err" v-if="errors.first('kode_sponsor')">@{{ errors.first('kode_sponsor') }}</span>
                        </div>                        
                    </div> 
                    <div class="form-group">
                        <label class="col-md-2 control-label">Nama/Lembaga</label>
                        <div class="col-md-7">
                            <input type="text" class="form-control" name="nama" v-model="recordSlot.name" v-validate="'required|max:191'">
                            <span class="err" v-if="errors.first('nama')">@{{ errors.first('nama') }}</span>
                        </div>                        
                    </div> 
                    <div class="form-group">
                        <label class="col-md-2 control-label">Tipe Sponsor</label>
                        <div class="col-md-7">
                            <select class="form-control" name="tipe_sponsor" v-model="recordSlot.type" v-validate="'max:191'">
                                <option value="">-Pilih Kartu ID-</option>
                                <option value="Perseorangan">Perseorangan</option>
                                <option value="Lembaga">Lembaga</option>
                            </select>
                            <span class="err" v-if="errors.first('tipe_sponsor')">@{{ errors.first('tipe_sponsor') }}</span>
                        </div>                        
                    </div> 
                    <div class="form-group">
                        <label class="col-md-2 control-label">Kartu ID</label>
                        <div class="col-md-7">
                            <select class="form-control" name="kartu_id" v-model="recordSlot.card_id" v-validate="'max:191'">
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
                         <label class="col-md-2 control-label">Asal Negara</label>
                         <div class="col-md-4">
                             <select class="form-control" v-model="recordSlot.nation_id" name="asal_negara_sponsor" v-validate="'required'">
                                <option value="" disabled>-Pilih Negara-</option>
                                  @foreach($nations as $nation)
                                    <option value="{{ $nation->id }}">{{$nation->nation}}</option>
                                  @endforeach
                                </select>
                            <span class="err" v-if="errors.first('asal_negara_sponsor')">@{{ errors.first('asal_negara_sponsor') }}</span>
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
                        <label class="col-md-2 control-label">Deskripsi Pekerjaan</label>
                        <div class="col-md-7">
                            <textarea class="form-control" rows="3" v-model="recordSlot.job_desc" name="deskripsi_pekerjaan" v-validate="'max:300'">@{{recordSlot.job_desc}}</textarea>
                            <span class="err" v-if="errors.first('deskripsi_pekerjaan')">@{{ errors.first('deskripsi_pekerjaan') }}</span>
                        </div>                        
                    </div>    
                    <div class="form-group">
                        <label class="col-md-2 control-label">Nama Bank</label>
                        <div class="col-md-7">
                            <input type="text" class="form-control" name="nama_bank" v-model="recordSlot.bank_name" v-validate="'max:191'">
                            <span class="err" v-if="errors.first('nama_bank')">@{{ errors.first('nama_bank') }}</span>
                        </div>                        
                    </div>  
                    <div class="form-group">
                        <label class="col-md-2 control-label">Cabang Bank</label>
                        <div class="col-md-7">
                            <input type="text" class="form-control" name="cabang_bank" v-model="recordSlot.bank_branch" v-validate="'max:191'">
                            <span class="err" v-if="errors.first('cabang_bank')">@{{ errors.first('cabang_bank') }}</span>
                        </div>                        
                    </div>  
                    <div class="form-group">
                        <label class="col-md-2 control-label">Kota Bank</label>
                        <div class="col-md-7">
                            <input type="text" class="form-control" name="kota_bank" v-model="recordSlot.bank_city" v-validate="'max:191'">
                            <span class="err" v-if="errors.first('kota_bank')">@{{ errors.first('kota_bank') }}</span>
                        </div>                        
                    </div>  
                    <div class="form-group">
                        <label class="col-md-2 control-label">Nomor Rekening</label>
                        <div class="col-md-7">
                            <input type="text" class="form-control" name="nomor_rekening" v-model="recordSlot.account_numb" v-validate="'max:191'">
                            <span class="err" v-if="errors.first('nomor_rekening')">@{{ errors.first('nomor_rekening') }}</span>
                        </div>                        
                    </div>   
                    <div class="form-group">
                        <label class="col-md-2 control-label">Pemilik Rekening</label>
                        <div class="col-md-7">
                            <input type="text" class="form-control" name="pemilik_rekening" v-model="recordSlot.account_name" v-validate="'max:191'">
                            <span class="err" v-if="errors.first('pemilik_rekening')">@{{ errors.first('pemilik_rekening') }}</span>
                        </div>                        
                    </div>    
                    <div class="form-group">
                        <label class="col-md-2 control-label">Sponsor Sejak</label>
                        <div class="col-md-7">
                            <input type="date" class="form-control" name="tanggal_daftar" v-model="recordSlot.start_date">
                        </div>                        
                    </div>   
                    <div class="form-group">
                        <label class="col-md-2 control-label">Catatan Khusus</label>
                        <div class="col-md-7">
                          <textarea class="form-control" rows="3" v-model="recordSlot.note" name="catatan_khusus" v-validate="'max:191'">@{{recordSlot.note}}</textarea>
                        </div>   
                        <span class="err" v-if="errors.first('catatan_khusus')">@{{ errors.first('catatan_khusus') }}</span>
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
                        <img v-bind:src="$store.state.apiUrl + 'appimages/sponsor/' + recordPicked.photo" class="img-responsive">
                    </div>
                    <div class="col-md-9">
                        <table class="table table-bordered first-fit">
                            <tr><td>Induk:</td><td>@{{ recordPicked.sponsor_numb }}</td></tr>
                            <tr><td>Nama<span v-if="recordPicked.type == 'Lembaga'"> Lembaga</span>:</td><td>@{{ recordPicked.name }}</td></tr>
                            <tr><td>Jenis Pengenal:</td><td>@{{ recordPicked.card }}</td></tr>
                            <tr><td>No. Pengenal:</td><td>@{{ recordPicked.id_numb }}</td></tr>
                            <tr><td>Asal Negara:</td><td>@{{ recordPicked.nation }}</td></tr>
                            <tr><td>Alamat:</td><td>@{{ recordPicked.address }}</td></tr>
                            <tr><td>Telepon:</td><td>@{{ recordPicked.contact }}</td></tr>
                            <tr><td>Email:</td><td>@{{ recordPicked.email }}</td></tr>
                            <tr><td>Pekerjaan:</td><td>@{{ recordPicked.job_desc }}</td></tr>
                            <tr><td>Nama Bank:</td><td>@{{ recordPicked.bank_name }}</td></tr>
                            <tr><td>Cabang:</td><td>@{{ recordPicked.bank_branch }}, @{{ recordPicked.bank_city }}</td></tr>
                            <tr><td>Rekening:</td><td>@{{ recordPicked.account_name }} - @{{ recordPicked.account_numb }}</td></tr>
                            <tr><td>Catatan Khusus:</td><td>@{{ recordPicked.note }}</td></tr>
                        </table>
                        <span style="color:red;"><i>Sponsorship since @{{ recordPicked.start_date }}</i></span>
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
    </sponsor-table>

            <!-- #END# CPU Usage -->
@endsection