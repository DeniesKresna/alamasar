<!-- CPU Usage -->
@extends('dashboard')
@section('content')
    <year-table inline-template>
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
                <!--<i class="fa fa-spin fa-spinner" v-if="isLoading"></i>-->
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
                                <th>Tahun Ajaran</th>
                                <th>Pengupdate</th>
                                <th>Modif Terakhir</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(record,index) in records.data">
                                <td>@{{index + records.from}}</td>
                                <td>@{{record.year}}</td>
                                <td>@{{record.pengupdate.name}}</td>
                                <td>@{{record.updated_at | toDMYHIS}}</td>
                                <td><div class="btn-group"><!--<button class="btn btn-info" v-on:click="recordDetail(record)" v-bind:title="'lihat ' + record.card"><i class="fa fa-eye"></i></button>--><button class="btn btn-warning" v-on:click="openModal(true, record)" v-bind:title="'edit ' + record.card"><i class="fa fa-pencil"></i></button><button class="btn btn-danger" v-on:click="deleteRecord(record)" v-bind:title="'hapus ' + record.card"><i class="fa fa-trash"></i></button><!--<button class="btn btn-success" v-on:click="deleteRecord(record)" v-if="record.isActive == 1" v-bind:title="'non-aktifkan ' + record.card"><i class="fa fa-toggle-on"></i></button><button class="btn btn-danger" v-on:click="deleteRecord(record)" v-else v-bind:title="'aktifkan ' + record.card"><i class="fa fa-toggle-off"></i></button>--></div></td>
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
                <h4 class="modal-title" id="defaultModalLabel"></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group">
                        <label class="col-md-2 control-label">@{{tableDescription}}</label>
                        <div class="col-md-7">
                            <input type="text" class="form-control" name="tahun_ajaran" v-model="recordSlot.year" v-validate="'required|max:191'">
                            <span class="err" v-if="errors.first('tahun_ajaran')">@{{ errors.first('tahun_ajaran') }}</span>
                        </div>                        
                    </div>               
                </div>  
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" v-on:click="modifyRecord">Simpan</button>
            </div>
        </div>
    </modal>
</div>
    </year-table>

            <!-- #END# CPU Usage -->
@endsection