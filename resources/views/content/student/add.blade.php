@extends('dashboard')
@section('content')
<student-add inline-template>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="box box-default">
            <div class="box-header with-border">
              <h3 class="box-title">Tambah Siswa Bea Siswa</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <loading v-bind:active.sync="isLoading" 
                    v-bind:can-cancel="false" 
                    v-bind:is-full-page="true">
                </loading>
            <div class="box-body">
            <form class="form form-horizontal">
                  <form-wizard v-bind:title="wizardTitle" subtitle="Isilah Formulir melalui tahapan di bawah ini" v-on:on-complete="modifyRecord">
                    <tab-content title="Data Siswa">
                      <div class="form-group">
                        <label class="col-md-2 control-label">ID Code</label>
                        <div class="col-md-7">
                          <input type="text" class="form-control" v-model="postData.student_numb"  name="nomor" v-validate="'required|max:191'">
                          <span class="err" v-if="errors.first('nomor')">@{{ errors.first('nomor') }}</span>
                        </div>                        
                      </div>
                      <div class="form-group">
                        <label class="col-md-2 control-label">Nama</label>
                        <div class="col-md-7">
                          <input type="text" class="form-control" v-model="postData.name" name="nama" v-validate="'required|max:191'">
                          <span class="err" v-if="errors.first('nama')">@{{ errors.first('nama') }}</span>
                        </div>                        
                      </div>
                      <div class="form-group">
                        <label class="col-md-2 control-label">Kartu ID</label>
                        <div class="col-md-4">
                          <select class="form-control" v-model="postData.card_id" name="kartu_ID" v-validate="'required'">
                            <option value="" disabled>-Pilih Kartu ID-</option>
                            @foreach($cards as $card)
                              <option value="{{ $card->id }}">{{$card->card}}</option>
                            @endforeach
                          </select>
                          <span class="err" v-if="errors.first('kartu_ID')">@{{ errors.first('kartu_ID') }}</span>
                        </div>                   
                      </div> 
                      <div class="form-group">
                        <label class="col-md-2 control-label">Nomor ID</label>
                        <div class="col-md-7">
                          <input type="text" class="form-control" v-model="postData.id_numb" name="nomor_ID" v-validate="'required|max:191'">
                          <span class="err" v-if="errors.first('nomor_ID')">@{{ errors.first('nomor_ID') }}</span>
                        </div>                        
                      </div>
                      <div class="form-group">
                        <label class="col-md-2 control-label">Email</label>
                        <div class="col-md-7">
                          <input type="email" class="form-control" v-model="postData.email" name="email" v-validate="'email'">
                          <span class="err" v-if="errors.first('email')">@{{ errors.first('email') }}</span>
                        </div>                        
                      </div>
                      <div class="form-group">
                        <label class="col-md-2 control-label">Telepon</label>
                        <div class="col-md-7">
                          <input type="text" class="form-control" v-model="postData.contact" name="telepon" v-validate="'max:191'">
                          <span class="err" v-if="errors.first('telepon')">@{{ errors.first('telepon') }}</span>
                        </div>                        
                      </div>
                      <div class="form-group">
                        <label class="col-md-2 control-label">Jenis Kelamin</label>
                        <div class="col-md-4">
                          <select class="form-control" v-model="postData.sex" name="kelamin" v-validate="'required'"><option value="L">Laki-laki</option><option value="P">Perempuan</option></select>
                          <span class="err" v-if="errors.first('kelamin')">@{{ errors.first('kelamin') }}</span>
                        </div>                        
                      </div>                      
                      <div class="form-group">
                        <label class="col-md-2 control-label">Alamat (Kartu ID)</label>
                        <div class="col-md-7">
                          <input type="text" class="form-control" v-model="postData.address" name="alamat_sesuai_kartu" v-validate="'max:191'">
                          <span class="err" v-if="errors.first('alamat_sesuai_kartu')">@{{ errors.first('alamat_sesuai_kartu') }}</span>
                        </div>                        
                      </div>                      
                      <div class="form-group">
                        <label class="col-md-2 control-label">Alamat (Domisili)</label>
                        <div class="col-md-7">
                          <input type="text" class="form-control" v-model="postData.domicile" name="alamat_domisili" v-validate="'max:191'">
                          <span class="err" v-if="errors.first('alamat_domisili')">@{{ errors.first('alamat_domisili') }}</span>
                        </div>                        
                      </div>                
                      <div class="form-group">
                        <label class="col-md-2 control-label">Tempat Lahir</label>
                        <div class="col-md-7">
                          <input type="text" class="form-control" v-model="postData.birth_place" name="tempat_lahir" v-validate="'max:191'">
                          <span class="err" v-if="errors.first('tempat_lahir')">@{{ errors.first('tempat_lahir') }}</span>
                        </div>                        
                      </div>              
                      <div class="form-group">
                        <label class="col-md-2 control-label">Tanggal Lahir</label>
                        <div class="col-md-4">
                          <input type="date" v-model="postData.birth_date" class="form-control" name="birth_date" placeholder="yyyy-mm-dd">
                        </div>                        
                      </div> 
                      <div class="form-group">
                        <label class="col-md-2 control-label">Catatan Khusus</label>
                        <div class="col-md-7">
                          <textarea class="form-control" rows="3" v-model="postData.note" name="catatan_khusus" v-validate="'max:191'">@{{postData.note}}</textarea>
                        </div>   
                        <span class="err" v-if="errors.first('catatan_khusus')">@{{ errors.first('catatan_khusus') }}</span>
                      </div>  
                    </tab-content>
                    <tab-content title="Informasi Tambahan">
                      <div class="form-group">
                        <label class="col-md-2 control-label">Agama</label>
                        <div class="col-md-4">
                          <select class="form-control" v-model="postData.religion_id" name="agama" v-validate="'required'">
                            <option value="" disabled>-Pilih Agama-</option>
                            @foreach($religions as $religion)
                              <option value="{{ $religion->id }}">{{$religion->religion}}</option>
                            @endforeach
                          </select>
                          <span class="err" v-if="errors.first('agama')">@{{ errors.first('agama') }}</span>
                        </div>                   
                      </div> 
                      <div class="form-group">
                        <label class="col-md-2 control-label">Pendidikan</label>
                        <div class="col-md-4">
                          <select class="form-control" v-model="postData.education_id" name="pendidikan" v-validate="'required'">
                            <option value="" disabled>-Pilih Pindidikan Terakhir-</option>
                            @foreach($educations as $education)
                              <option value="{{ $education->id }}">{{$education->education}}</option>
                            @endforeach
                          </select>
                          <span class="err" v-if="errors.first('pendidikan')">@{{ errors.first('pendidikan') }}</span>
                        </div>                   
                      </div>     
                      <div class="form-group">
                        <label class="col-md-2 control-label">Deskripsi Pendidikan</label>
                        <div class="col-md-7">
                          <textarea class="form-control" rows="3" v-model="postData.education_desc" name="keterangan_pendidikan" v-validate="'max:191'">@{{postData.education_desc}}</textarea>
                        </div>   
                        <span class="err" v-if="errors.first('keterangan_pendidikan')">@{{ errors.first('keterangan_pendidikan') }}</span>
                      </div>       
                      <div class="form-group">
                        <label class="col-md-2 control-label">Status</label>
                        <div class="col-md-4">
                          <select class="form-control" v-model="postData.status" name="status" v-validate="'required'">
                            <option value="current">Current</option>
                            <option value="lulus">Lulus</option>
                            <option value="dropout">Drop Out</option>
                          </select>
                          <span class="err" v-if="errors.first('status')">@{{ errors.first('status') }}</span>
                        </div>  
                      </div>     
                      <div class="form-group">
                        <label class="col-md-2 control-label">Keterangan Status</label>
                        <div class="col-md-7">
                          <input type="text" class="form-control" v-model="postData.status_desc" name="keterangan_status" v-validate="'max:191'">
                        </div>   
                        <span class="err" v-if="errors.first('keterangan_status')">@{{ errors.first('keterangan_status') }}</span>
                      </div>    
                      <div class="form-group">
                        <label class="col-md-2 control-label">Nama BANK</label>
                        <div class="col-md-7">
                          <input type="text" class="form-control" v-model="postData.bank_name" name="nama_bank" v-validate="'max:191'">
                          <span class="err" v-if="errors.first('nama_bank')">@{{ errors.first('nama_bank') }}</span>
                        </div>                        
                      </div>                
                      <div class="form-group">
                        <label class="col-md-2 control-label">Cabang</label>
                        <div class="col-md-7">
                          <input type="text" class="form-control" v-model="postData.bank_branch" name="cabang_bank" v-validate="'max:191'">
                          <span class="err" v-if="errors.first('cabang_bank')">@{{ errors.first('cabang_bank') }}</span>
                        </div>                        
                      </div>         
                      <div class="form-group">
                        <label class="col-md-2 control-label">Kota BANK</label>
                        <div class="col-md-7">
                          <input type="text" class="form-control" v-model="postData.bank_city" name="kota_bank" v-validate="'max:191'">
                          <span class="err" v-if="errors.first('kota_bank')">@{{ errors.first('kota_bank') }}</span>
                        </div>                        
                      </div>                 
                      <div class="form-group">
                        <label class="col-md-2 control-label">No Rekening</label>
                        <div class="col-md-7">
                          <input type="text" class="form-control" v-model="postData.account_numb" name="no_rekening" v-validate="'max:191'">
                          <span class="err" v-if="errors.first('no_rekening')">@{{ errors.first('no_rekening') }}</span>
                        </div>                        
                      </div>                
                      <div class="form-group">
                        <label class="col-md-2 control-label">Pemilik Rekening</label>
                        <div class="col-md-7">
                          <input type="text" class="form-control" v-model="postData.account_name" name="nama_pemilik_rekening" v-validate="'max:191'">
                          <span class="err" v-if="errors.first('nama_pemilik_rekening')">@{{ errors.first('nama_pemilik_rekening') }}</span>
                        </div>                        
                      </div>                          
                     </tab-content>
                     <tab-content title="Informasi Keluarga">
                      <div class="form-group">
                        <label class="col-md-2 control-label">No KK</label>
                        <div class="col-md-7">
                          <input type="text" class="form-control" v-model="postData.cc_numb" name="nomor_kartu_keluarga" v-validate="'max:191'">
                          <span class="err" v-if="errors.first('nomor_kartu_keluarga')">@{{ errors.first('nomor_kartu_keluarga') }}</span>
                        </div>                        
                      </div>
                        <button class="btn btn-sm btn-default" type="button" v-on:click="openModal(false)"><span class="fa fa-plus"></span> Anggota Keluarga</button>
                       <div v-if="families.length > 0">
                         <table class="table table-bordered">
                            <thead>
                                <tr>
                                  <th>No</th>
                                  <th>Status</th>
                                  <th>NIK</th>
                                  <th>Nama</th>
                                  <th>Telepon</th>
                                  <th>Fungsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(family,index) in families">
                                    <td>@{{ index + 1 }}</td>
                                    <td>@{{ family.type }}</td>
                                    <td>@{{ family.nik }}</td>
                                    <td>@{{ family.name }}</td>
                                    <td>@{{ family.contact }}</td>
                                    <td><div class="btn-group"><button type="button" class="btn btn-sm btn-warning" v-on:click="openModal(true,family)"><div class="fa fa-pencil"></div></button><button type="button" class="btn btn-sm btn-danger" v-on:click="deleteRecord(family)"><div class="fa fa-trash"></div></button></div></td>
                                </tr>
                            </tbody>
                         </table>
                       </div>
                       <div v-else>
                          Belum ada data keluarga
                       </div>
                     </tab-content>
                     <tab-content title="Pendidikan">
                       <button class="btn btn-sm btn-default" type="button" v-on:click="openScoreModal(false)"><span class="fa fa-plus"></span> Daftar Nilai Siswa</button>
                       <div v-if="scores.length > 0">
                         <table class="table table-bordered">
                            <thead>
                                <tr>
                                  <th>No</th>
                                  <th>Tahun Ajaran</th>
                                  <th>Jenis</th>
                                  <th>Nilai</th>
                                  <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(score,index) in scores">
                                    <td>@{{ index + 1 }}</td>
                                    <td>@{{ score.year }}</td>
                                    <td>@{{ score.type }}</td>
                                    <td>@{{ score.score }}</td>
                                    <td><div class="btn-group"><button type="button" class="btn btn-sm btn-warning" v-on:click="openScoreModal(true,score)"><div class="fa fa-pencil"></div></button><button type="button" class="btn btn-sm btn-danger" v-on:click="deleteScoreRecord(score)"><div class="fa fa-trash"></div></button></div></td>
                                </tr>
                            </tbody>
                         </table>
                        </div>
                        <div v-else>
                          Belum ada data nilai
                        </div>
                        <button class="btn btn-sm btn-default" type="button" v-on:click="openEduNeedModal(false)"><span class="fa fa-plus"></span> Daftar Kebutuhan Pendidikan</button>
                       <div v-if="eduNeeds.length > 0">
                         <table class="table table-bordered">
                            <thead>
                                <tr>
                                  <th>No</th>
                                  <th>Kebutuhan</th>
                                  <th>Harga</th>
                                  <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(eduNeed,index) in eduNeeds">
                                    <td>@{{ index + 1 }}</td>
                                    <td>@{{ eduNeed.eduNeed }}</td>
                                    <td>@{{ eduNeed.price }}</td>
                                    <td><div class="btn-group"><button type="button" class="btn btn-sm btn-warning" v-on:click="openEduNeedModal(true,eduNeed)"><div class="fa fa-pencil"></div></button><button type="button" class="btn btn-sm btn-danger" v-on:click="deleteEduNeedRecord(eduNeed)"><div class="fa fa-trash"></div></button></div></td>
                                </tr>
                            </tbody>
                         </table>
                        </div>
                        <div v-else>
                          Belum ada data kebutuhan pendidikan
                        </div>
                     </tab-content>
                  </form-wizard>
                  <modal name="modal_eduneed" height="auto" width="60%" :scrollable="true">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="defaultModalLabel">Modifikasi Kebutuhan Pendidikan</h4>
                        </div>
                        <div class="modal-body">
                          <div class="form-group">
                            <label class="col-md-2 control-label">Item</label>
                            <div class="col-md-7">
                              <input type="text" class="form-control" v-model="eduNeedSlot.eduNeed" name="nama_kebutuhan" v-validate="'required|max:191'" placeholder="SPP, alat tulis, dll">
                              <span class="err" v-if="errors.first('nama_kebutuhan')">@{{ errors.first('nama_kebutuhan') }}</span>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="col-md-2 control-label">Harga</label>
                            <div class="col-md-7">
                              <input type="text" class="form-control" v-model="eduNeedSlot.price" name="harga_kebutuhan" v-validate="'required|numeric'">
                              <span class="err" v-if="errors.first('harga_kebutuhan')">@{{ errors.first('harga_kebutuhan') }}</span>
                            </div>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-info" v-on:click="modifyEduNeed">SIMPAN KEBUTUHAN</button>
                          <button type="button" class="btn btn-warning" v-on:click="closeModal">CLOSE</button>
                        </div>
                    </div>
                  </modal>
                  <modal name="modal_nilai" height="auto" width="60%" :scrollable="true">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="defaultModalLabel">Tambah Nilai</h4>
                        </div>
                        <div class="modal-body">
                          <div class="form-group">
                            <label class="col-md-2 control-label">Tipe</label>
                            <div class="col-md-7">
                              <select class="form-control" v-model="nilaiSlot.type" name="tipe_nilai" v-validate="'required'">
                                <option value="nb">Nilai Biasa (0-100)</option>
                                <option value="ip">Indeks Prestasi (0-4)</option>
                              </select>
                              <span class="err" v-if="errors.first('tipe_nilai')">@{{ errors.first('tipe_nilai') }}</span>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="col-md-2 control-label">Tahun Ajaran</label>
                            <div class="col-md-7">
                              <select class="form-control" v-model="nilaiSlot.year" name="tahun_ajaran" v-validate="'required'">
                                <option value="">-Pilih Tahun Ajaran-</option>
                                @foreach($years as $year)
                                  <option value="{{$year->year}}">{{$year->year}}</option>
                                @endforeach
                              </select>
                              <span class="err" v-if="errors.first('tahun_ajaran')">@{{ errors.first('tahun_ajaran') }}</span>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="col-md-2 control-label">Nilai</label>
                            <div class="col-md-7">
                              <input type="text" class="form-control" v-model="nilaiSlot.score" name="nilai" v-validate="'required|decimal:2|max:100'">
                              <span class="err" v-if="errors.first('nilai')">@{{ errors.first('nilai') }}</span>
                            </div>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-info" v-on:click="modifyNilai">SIMPAN NILAI</button>
                          <button type="button" class="btn btn-warning" v-on:click="closeModal">CLOSE</button>
                        </div>
                    </div>
                  </modal>
                  <modal name="modal_tambah" height="auto" width="60%" :scrollable="true">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="defaultModalLabel">Tambah Anggota</h4>
                        </div>
                        
                            <div class="modal-body">
                                <div class="row">
                            <div class="form-group">
                              <label class="col-md-2 control-label">NIK</label>
                              <div class="col-md-7">
                                <input type="text" class="form-control" v-model="familySlot.nik" name="nik_anggota_keluarga" v-validate="'required|max:191'" v-on:change="checkFamilyExist">
                                <span class="err" v-if="errors.first('nik_anggota_keluarga')">@{{ errors.first('nik_anggota_keluarga') }}</span>
                              </div>                        
                            </div>
                                    <div class="form-group">
                              <label class="col-md-2 control-label">Tipe</label>
                              <div class="col-md-4">
                                <select class="form-control" v-model="familySlot.type" name="type_anggota_keluarga" v-validate="'required'">
                                  <option value="" disabled>-Pilih Tipe-</option>
                                  <option value="Ayah">Ayah</option>
                                  <option value="Ayah Tiri">Ayah Tiri</option>
                                  <option value="Ibu">Ibu</option>
                                  <option value="Ibu Tiri">Ibu Tiri</option>
                                  <option value="Saudara Laki-laki">Saudara Laki-laki</option>
                                  <option value="Saudara Perempuan">Saudara Perempuan</option>
                                  <option value="Saudara Tiri">Saudara Tiri</option>
                                </select>
                                <span class="err" v-if="errors.first('type_anggota_keluarga')">@{{ errors.first('type_anggota_keluarga') }}</span>
                              </div>                   
                            </div> 
                                    <div class="form-group">
                              <label class="col-md-2 control-label">Nama</label>
                              <div class="col-md-7">
                                <input type="text" class="form-control" v-model="familySlot.name" name="nama_anggota_keluarga" v-validate="'required|max:191'">
                                <span class="err" v-if="errors.first('nama_anggota_keluarga')">@{{ errors.first('nama_anggota_keluarga') }}</span>
                              </div>                        
                            </div>
                            <div class="form-group">
                              <label class="col-md-2 control-label">Email</label>
                              <div class="col-md-7">
                                <input type="email" class="form-control" v-model="familySlot.email" name="email_anggota_keluarga" v-validate="'max:191|email'">
                                <span class="err" v-if="errors.first('email_anggota_keluarga')">@{{ errors.first('email_anggota_keluarga') }}</span>
                              </div>                        
                            </div>
                            <div class="form-group">
                              <label class="col-md-2 control-label">Telepon</label>
                              <div class="col-md-7">
                                <input type="text" class="form-control" v-model="familySlot.contact" name="telepon_anggota_keluarga" v-validate="'max:191'">
                                <span class="err" v-if="errors.first('telepon_anggota_keluarga')">@{{ errors.first('telepon_anggota_keluarga') }}</span>
                              </div>                        
                            </div><!--
                            <div class="form-group">
                              <label class="col-md-2 control-label">Jenis Kelamin</label>
                              <div class="col-md-4">
                                <select class="form-control" v-model="familySlot.sex"><option value="L">Laki-laki</option><option value="P">Perempuan</option></select>
                              </div>                        
                            </div>  -->                    
                            <div class="form-group">
                              <label class="col-md-2 control-label">Alamat</label>
                              <div class="col-md-7">
                                <input type="text" class="form-control" v-model="familySlot.address" name="alamat_anggota_keluarga" v-validate="'max:191'">
                                <span class="err" v-if="errors.first('alamat_anggota_keluarga')">@{{ errors.first('alamat_anggota_keluarga') }}</span>
                              </div>                        
                            </div>                 
                            <div class="form-group">
                              <label class="col-md-2 control-label">Tempat Lahir</label>
                              <div class="col-md-7">
                                <input type="text" class="form-control" v-model="familySlot.birth_place" name="tempat_lahir_anggota_keluarga" v-validate="'max:191'">
                                <span class="err" v-if="errors.first('tempat_lahir_anggota_keluarga')">@{{ errors.first('tempat_lahir_anggota_keluarga') }}</span>
                              </div>                        
                            </div>              
                            <div class="form-group">
                              <label class="col-md-2 control-label">Tanggal Lahir</label>
                              <div class="col-md-4">
                                <input type="date" v-model="familySlot.birth_date" class="form-control" placeholder="yyyy-mm-dd">
                              </div>                        
                            </div> 
                            <div class="form-group">
                              <label class="col-md-2 control-label">Agama</label>
                              <div class="col-md-4">
                                <select class="form-control" v-model="familySlot.religion_id" name="agama_anggota_keluarga" v-validate="'required'">
                                  <option value="" disabled>-Pilih Agama-</option>
                                  @foreach($religions as $religion)
                                    <option value="{{ $religion->id }}">{{$religion->religion}}</option>
                                  @endforeach
                                </select>
                                <span class="err" v-if="errors.first('agama_anggota_keluarga')">@{{ errors.first('agama_anggota_keluarga') }}</span>
                              </div>                   
                            </div> 
                            <div class="form-group">
                              <label class="col-md-2 control-label">Pendidikan</label>
                              <div class="col-md-4">
                                <select class="form-control" v-model="familySlot.education_id" name="pendidikan_anggota_keluarga" v-validate="'required'">
                                  <option value="" disabled>-Pilih Pindidikan Terakhir-</option>
                                  @foreach($educations as $education)
                                    <option value="{{ $education->id }}">{{$education->education}}</option>
                                  @endforeach
                                </select>
                                <span class="err" v-if="errors.first('pendidikan_anggota_keluarga')">@{{ errors.first('pendidikan_anggota_keluarga') }}</span>
                              </div>                   
                            </div> 
                            <div class="form-group">
                              <label class="col-md-2 control-label">Pekerjaan</label>
                              <div class="col-md-4">
                                <select class="form-control" v-model="familySlot.occupation_id" name="pekerjaan_anggota_keluarga" v-validate="'required'">
                                  <option value="" disabled>-Pilih Pekerjaan-</option>
                                  @foreach($occupations as $occupation)
                                    <option value="{{ $occupation->id }}">{{$occupation->occupation}}</option>
                                  @endforeach
                                </select>
                                <span class="err" v-if="errors.first('pekerjaan_anggota_keluarga')">@{{ errors.first('pekerjaan_anggota_keluarga') }}</span>
                              </div>                   
                            </div> 
                            <div class="form-group">
                              <label class="col-md-2 control-label">Deskripsi Pekerjaan</label>
                              <div class="col-md-7">
                                <textarea class="form-control" v-model="familySlot.occupation_desc" name="deskripsi_pekerjaan_keluarga" v-validate="'max:255'" rows="4"></textarea>
                                <span class="err" v-if="errors.first('deskripsi_pekerjaan_keluarga')">@{{ errors.first('deskripsi_pekerjaan_keluarga') }}</span>
                              </div>                   
                            </div>  
                            <div class="form-group">
                              <label class="col-md-2 control-label">Status</label>
                              <div class="col-md-4">
                                <select class="form-control" v-model="familySlot.marital_status" name="status_nikah_anggota_keluarga" v-validate="'required'">
                                  <option value="" disabled>-Pilih Status Perkawinan-</option>
                                  <option value="Belum Menikah">Belum Menikah</option>
                                  <option value="Menikah">Menikah</option>
                                  <option value="Bercerai">Bercerai</option>
                                </select>
                                <span class="err" v-if="errors.first('status_nikah_anggota_keluarga')">@{{ errors.first('status_nikah_anggota_keluarga') }}</span>
                              </div>                   
                            </div>

                            <div class="form-group">
                              <label class="col-md-2 control-label">Meninggal?</label>
                              <div class="col-md-4">
                                <select class="form-control" v-model="familySlot.isDied" name="status_hidup_anggota_keluarga" v-validate="'required'">
                                  <option value="0">Masih hidup</option>
                                  <option value="1">Meninggal</option>
                                </select>
                                <span class="err" v-if="errors.first('status_hidup_anggota_keluarga')">@{{ errors.first('status_hidup_anggota_keluarga') }}</span>
                              </div>                   
                            </div>  
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-info" v-on:click="modifyFamily">SIMPAN ANGGOTA</button>
                                <button type="button" class="btn btn-warning" v-on:click="closeModal">CLOSE</button>
                            </div>
                        
                    </div>
                </modal>
            </form>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
             Silahkan memasukkan pengisian data
            </div>
          </div>
          <!-- /.box -->
    </div>
    
</div>
</student-add>
@endsection