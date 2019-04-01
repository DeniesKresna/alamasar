<!-- CPU Usage -->
@extends('dashboard')
@section('content')
    <student-table inline-template>
  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="box box-default">
            <div class="box-header with-border">
              <h3 class="box-title">Daftar Siswa</h3>

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
                            <a class="btn btn-success" href="{{ route('student.add') }}"><i class="fa fa-plus"></i> Tambah</a>
                            <!--<button class="btn btn-success" v-on:click="openModal(false)"><i class="fa fa-plus"></i> Tambah</button>-->
                        </div>
                    </div>@endif
                </div>
                <div v-if="records.total > 0">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Foto</th>
                                <th><a href="#" v-on:click="sortRecords('student_numb')">ID Code</a></th>
                                <th><a href="#" v-on:click="sortRecords('name')">Nama</a></th>
                                <th><a href="#" v-on:click="sortRecords('sex')">L/P</a></th>
                                <th>Nilai</th>
                                <th><a href="#" v-on:click="sortRecords('education_desc')">Pendidikan</a></th>
                                <th><a href="#" v-on:click="sortRecords('status')">Status</a></th>
                                <th>Keterangan Status</th>
                                @if(in_array(Auth::User()->level, [1,2]))<th>Action</th>@endif
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(record,index) in records.data">
                                <td>@{{index + records.from}}</td>
                                <td><img v-bind:src="$store.state.apiUrl + '/appimages/student/' +record.photo + '?time=' + updatefototime" class="img-circle" width="50" /></td>
                                <td>@{{record.student_numb}}</td>
                                <td>@{{record.name}}</td>
                                <td>@{{record.sex}}</td>
                                <td><span v-if="record.scores.length > 0"><button class="btn btn-info" v-bind:title="'nilai '+record.name" @click="openScoreModal(record)"><span class="fa fa-graduation-cap"></span></button></span><span v-else>No Data</span></td>
                                <td style="word-wrap: break-word;min-width: 160px;max-width: 160px;">@{{record.education_desc}}</td>
                                <td>@{{record.status}}</td>
                                <td>@{{record.status_desc}}</td>
                                <td><div class="btn-group"><a v-bind:href="$store.state.apiUrl + 'student/profile/' + record.id" class="btn btn-info" v-bind:title="'lihat ' + record.name"><i class="fa fa-eye"></i></a>@if(in_array(Auth::User()->level, [1,2]))<button class="btn btn-warning" v-on:click="goToEditPage(record.id)" v-bind:title="'edit ' + record.name"><i class="fa fa-pencil"></i></button><button class="btn btn-success" v-on:click="openModalDp(record)" v-bind:title="'ganti foto ' + record.name"><i class="fa fa-file-image-o"></i></button><button class="btn btn-danger" v-on:click="deleteRecord(record)" v-bind:title="'hapus ' + record.name"><i class="fa fa-trash"></i></button>@endif<!--<button class="btn btn-primary" v-on:click="openModalDonate(record)" v-bind:title="'donasikan ' + record.name"><i class="fa fa-handshake-o"></i></button>-->

                                    <!--<button class="btn btn-success" v-on:click="deleteRecord(record)" v-if="record.isActive == 1" v-bind:title="'non-aktifkan ' + record.name"><i class="fa fa-toggle-on"></i></button><button class="btn btn-danger" v-on:click="deleteRecord(record)" v-else v-bind:title="'aktifkan ' + record.name"><i class="fa fa-toggle-off"></i></button>--></div></td>
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
    <modal name="modal_score" height="auto" width="60%" :scrollable="true">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="defaultModalLabel">Statistik Nilai @{{ recordSlot.name }}</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <line-chart v-bind:chartdata="scoreCollection" v-bind:options="scoreGraphOption"></line-chart>
                    </div>           
                </div>  
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" v-on:click="closeModal">Tutup</button>
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
                <button type="button" class="btn btn-info" v-on:click="closeModal">Tutup</button><button type="button" class="btn btn-success" v-on:click="changeDp" v-if="image != ''">Ganti Gambar</button>
            </div>
        </div>
    </modal>
    <modal name="modal_donate" height="auto" width="90%" :scrollable="true">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="defaultModalLabel">Donasi untuk <b>@{{ recordSlot.name }}</b>; Kebutuhan Tahunan: @{{ student_amount }}</h4>
            </div>
            <div class="modal-body">
                <div class="panel box box-primary">
                  <div class="box-header with-border">
                    <span class="btn btn-info" v-on:click="openDonateCreateForm">
                        Modif Donasi untuk @{{ recordSlot.name }}
                    </span>
                  </div>
                  <div id="collapseOne" class="panel-collapse collapse" v-bind:class="{in: isDonateAdd}">
                    <div class="box-body">
                            <div class="form-group col-md-6">
                                <multiselect v-model="recordDonate.coordinator" v-bind:options="coordinators" placeholder="Select one" label="name" v-bind:custom-label="nameWithNumbCoor"></multiselect>
                            </div>
                            <div class="form-group col-md-6">
                                <multiselect v-model="recordDonate.sponsor" v-bind:options="sponsors" placeholder="Select one" label="name" v-bind:custom-label="nameWithNumbSpons"></multiselect>
                            </div>
                            <div class="form-group col-md-12">
                                <multiselect v-model="recordDonate.years" v-bind:options="years" v-bind:multiple="true" placeholder="Select Year" label="year" track-by="id"></multiselect>
                            </div>
                            <div class="form-group col-md-2">
                                <input type="text" class="form-control" name="dana" v-model="recordDonate.amount" placeholder="Jumlah Dana">
                                <span class="err" v-if="errors.first('dana')">@{{ errors.first('dana') }}</span>
                            </div>
                            <div class="form-group col-md-2">
                                <input type="date" class="form-control" name="tanggal_pengiriman" v-model="recordDonate.send_time" placeholder="Tanggal Pengiriman" v-validate="'required'">
                                <span class="err" v-if="errors.first('tanggal_pengiriman')">@{{ errors.first('tanggal_pengiriman') }}</span>
                            </div>
                            <div class="form-group col-md-2">
                                <input type="file" class="form-control" name="nota" placeholder="Gambar Nota" v-validate="'image|size:512'" v-on:change="getNota">
                                <span class="err" v-if="errors.first('nota')">@{{ errors.first('nota') }}</span>
                            </div>
                            <div class="form-group col-md-1">
                                <div>
                                    <button class="btn btn-success" v-on:click="modifyDonate"><span class="fa fa-floppy-o"></span></button>
                                </div>                        
                            </div> 
                    </div>
                  </div>
                </div>
                <div v-if="donates.length > 0">
                    <ul class="products-list product-list-in-box">
                        <!-- /.item -->
                        <li class="item" v-for="(dnt, index) in donates">
                          <div class="product-img">
                            <img v-bind:src="$store.state.apiUrl + 'appimages/sponsor/' + dnt.sponsor.photo" v-bind:alt="dnt.sponsor.name">
                          </div>
                          <div class="product-info">
                            <a href="#" class="product-title">dikoordinatori: @{{ dnt.coordinator.name }}
                              <span class="label label-info pull-right">Rp. @{{ dnt.amount }} ,00</span></a>
                            <span class="product-description">
                                  Dibayarkan pada tanggal: @{{ dnt.send_time }} oleh <b>@{{ dnt.sponsor.name }}</b> untuk periode <span v-for="yr in dnt.years"><b>@{{ yr.year }}</b>; </span> <!--[<a href="#" v-on:click="openDonateEditForm(dnt)" class="fa fa-pencil" style="color: orange;"></a>]-->[<a href="#" v-on:click="deleteDonate(dnt)" class="fa fa-trash" style="color: red;"></a>]
                            </span>
                          </div>
                        </li>
                      </ul>
                </div>
                <div v-else>
                    Siswa ini belum diberikan donasi
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" v-on:click="closeModal">Tutup</button>
            </div>
        </div>
    </modal>
</div>
    </student-table>

            <!-- #END# CPU Usage -->
@endsection