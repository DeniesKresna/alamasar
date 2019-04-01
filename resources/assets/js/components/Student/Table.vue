<script>
import LineChart from '../../LineChart.js';
import Multiselect from 'vue-multiselect';
import Loading from 'vue-loading-overlay';
    import 'vue-loading-overlay/dist/vue-loading.css';

    export default{
        components: {
          LineChart
        },
        components: {
          LineChart, Multiselect, Loading
        },
        data(){
            return{
                tableDescription: 'Users',
                tableName: 'student',
                records: {},
                recordPicked: {},
                recordSlot:{
                    level: 3,
                    gender: 'L'
                },
                recordDonate:{
                    sponsor: '',
                    coordinator: ''
                },
                isLoading: false,
                isDonateEditing: false,
                isViewLoading: false,
                search: '',
                rowCount: 10,
                student_amount: 0,
                image: '',
                donateImage: '',
                scoreCollection: null,
                scoreGraphOption: null,
                updatefototime: new Date().getTime(),
                isDonateAdd: false,
                coordinators: [],
                sponsors: [],
                donates: [],
                years: [],
                sortPar:{
                    student_numb: 'desc',
                    name: 'desc',
                    sex: 'desc',
                    education_desc: 'desc'
                }
            }
        },
        mounted(){
            this.scoreGraphOption = {
                scales: {
                    yAxes: [{
                        ticks: {
                            suggestedMin: 0,    // minimum will be 0, unless there is a lower value.
                            // OR //
                            beginAtZero: true,   // minimum value will be 0.
                            suggestedMax: 100,
                            stepSize: 5
                        }
                    }]
                },
                responsive: true,
                maintainAspectRatio: false,
            };
            axios.get(this.$store.state.apiUrl + 'coordinators_sponsors').then(response=>{
                this.isLoading = true;
                this.coordinators = response.data.coordinators;
                this.sponsors = response.data.sponsors;
                this.years = response.data.years;
                this.isLoading = false;
                this.getRecords();
            });
        },
        methods: {
            getRecords: function(pg=1, sortfield='student_numb'){
                this.isLoading = true;
                axios.get(this.$store.state.apiUrl + this.tableName + '?page=' + pg + '&search=' + this.search + '&rows=' + this.rowCount + '&sortfield=' + sortfield + '&sortmode=' + this.sortPar[sortfield]).then(response=>{
                    this.records = response.data.records;
                    this.isLoading = false;
                }).catch(error => {
                    console.log(error.response);
                    this.ajaxResponse(error,'error');
                });
            },
            recordDetail: function(rcd){
                axios.get(this.$store.state.apiUrl + this.tableName + '/' + rcd.id).then(response=>{
                    this.recordPicked = response.data.record;
                    this.isLoading = false;
                    this.$modal.show('modal_lihat');
                }).catch(error => {
                    this.$swal(error.response.statusText, "Terdapat kesalahan di server. mohon menghubungi Admin", "error");
                    this.isLoading = false;
                    console.log(error.response);
                });
            },
            deleteRecord: function(rcd){
                this.$swal({
                  title: "Hapus Data",
                  text: "Penghapusan data akan berpengaruh terhadap tabel lain yang berhubungan",
                  icon: "warning",
                  buttons: true,
                  dangerMode: true,
                })
                .then((willDelete) => {
                  if (willDelete) {
                    this.isLoading = true;
                    axios.delete(this.$store.state.apiUrl + this.tableName + '/' + rcd.student_numb).then(response=>{
                        this.$swal("Berhasil",response.data.message,"success");
                        this.ajaxResponse(response,'success');
                    }).catch(error => {
                        console.log(error.response);
                        this.ajaxResponse(error,'error');
                    });
                  } else {
                    this.$swal("Dibatalkan");
                  }
                });
            },          
            openViewModal: function(mode, rcd){
                this.$modal.show('modal_lihat');
            },
            sortRecords: function(fld){
                if(this.sortPar[fld]=='asc'){
                    this.sortPar[fld]='desc';
                }
                else{
                    this.sortPar[fld]='asc';
                }
                this.getRecords(1,fld);
            },
            openScoreModal: function(std){
                this.recordSlot = std
                var lbl = std.scores.map((a)=>{ return a.year });
                var dt = [];
                for(var i=0; i<std.scores.length; i++){
                    if(std.scores[i].type == 'ip'){
                        std.scores[i].score = parseInt(parseFloat(std.scores[i].score)/4*100);
                    }
                    else{
                        std.scores[i].score = parseInt(std.scores[i].score);
                    }
                    dt.push(std.scores[i].score);
                }
                this.scoreCollection = {
                    labels: lbl,
                    datasets: [{
                        label: 'Nilai ' + std.name,
                        backgroundColor: '#f87979',
                        data: dt
                    }]
                };
                console.log(lbl);
                console.log(dt);
                this.$modal.show('modal_score');
            },
            closeModal: function(){
                this.$modal.hide('modal_dp');
                this.$modal.hide('modal_score');
                this.$modal.hide('modal_donate');
            },
            goToEditPage: function(id){
                window.location.href = this.$store.state.apiUrl + 'student/add/page/' + id;
            },
            openModalDonate: function(rcd){
                this.recordSlot = rcd;
                this.isLoading  = true;
                axios.get(this.$store.state.apiUrl + 'donate/student/' + rcd.id).then(response=>{
                    this.donates = response.data.donates;
                    this.student_amount = response.data.student_amount;
                    this.isLoading  = false;
                    this.$modal.show('modal_donate');
                });
            },
            openModalDp: function(rcd){
                this.recordSlot = rcd;
                this.$modal.show('modal_dp');
            },
            getDp: function(e){
                this.image = e.target.files[0];
                console.log(this.image);
            },
            changeDp: function(){
                this.$validator.validate().then(result =>{
                    if(result){
                        this.isLoading = true;
                        var fd = new FormData();
                        fd.append('image',this.image);
                        axios.post(this.$store.state.apiUrl + this.tableName + '/dp/' + this.recordSlot.student_numb, fd, {
                            headers: {
                                'Content-Type': 'multipart/form-data',
                            }
                        }).then(response=>{
                            this.ajaxResponse(response, 'success');
                            this.image = '';
                            this.updatefototime= new Date().getTime();
                            this.closeModal();
                        }).catch(error=>{
                            console.log(error.response);
                            this.closeModal();
                        });
                    }
                    else{
                        alert("Masukan gambar dengan benar");
                    }
                });
            },
            openDonateCreateForm: function(){
                if(this.isDonateAdd == false){
                    this.recordDonate={};
                    this.recordDonate.send_time = '2010-01-01';
                    this.isDonateAdd = true;
                }
                else{
                    this.isDonateAdd = false;
                }
            },
            openDonateEditForm: function(rcd){
                if(this.isDonateAdd == false){
                    this.recordDonate=rcd;
                    this.isDonateAdd = true;
                }
                else{
                    this.isDonateAdd = false;
                }
            },
            getNota: function(e){
                this.image = e.target.files[0];
                console.log(this.image);
            },
            modifyDonate: function(){
                this.$validator.validate().then(result =>{
                    if(result){
                        this.isLoading = true;
                        var fd = new FormData();
                        var arrayYear = this.recordDonate.years.map((a)=>{ return a.id; });
                        console.log(arrayYear);
                        fd.append('coordinator_id',this.recordDonate.coordinator.id);
                        fd.append('sponsor_id',this.recordDonate.sponsor.id);
                        fd.append('amount',this.recordDonate.amount);
                        fd.append('send_time',this.recordDonate.send_time);
                        fd.append('student_id',this.recordSlot.id);
                        fd.append('image',this.image);
                        for (var i = 0; i < arrayYear.length; i++) {
                            fd.append('years[]', arrayYear[i]);
                        }
                        axios.post(this.$store.state.apiUrl + 'donate', fd, {
                            headers: {
                                'Content-Type': 'multipart/form-data',
                            }
                        }).then(response=>{
                            this.donateImage = '';
                            this.updatefototime= new Date().getTime();
                            this.$swal("Berhasil",response.data.message ,"success");
                            axios.get(this.$store.state.apiUrl + 'donate/student/' + this.recordSlot.id).then(response=>{
                                this.donates = response.data.donates;
                                this.isDonateAdd = false;
                                this.isLoading  = false;
                            });
                        }).catch(error=>{
                            console.log(error.response);
                            this.isLoading  = false;
                        });
                    }
                    else{
                        alert("Masukan formulir dengan benar");
                    }
                });
            },
            deleteDonate: function(dnt){
                this.$swal({
                  title: "Hapus Data",
                  text: "Penghapusan data akan berpengaruh terhadap tabel lain yang berhubungan",
                  icon: "warning",
                  buttons: true,
                  dangerMode: true,
                })
                .then((willDelete) => {
                  if (willDelete) {
                    this.isLoading = true;
                    axios.delete(this.$store.state.apiUrl + 'donate/' + dnt.id).then(response=>{
                        this.$swal("Berhasil",response.data.message,"success");
                        axios.get(this.$store.state.apiUrl + 'donate/student/' + this.recordSlot.id).then(response=>{
                                this.donates = response.data.donates;
                                this.isDonateAdd = false;
                                this.isLoading  = false;
                            });
                    }).catch(error => {
                        console.log(error.response);
                        this.ajaxResponse(error,'error');
                    });
                  } else {
                    this.$swal("Dibatalkan");
                  }
                });
            },
            resetGetRecords: function(){
                this.search = '';
                this.getRecords();
            },
            ajaxResponse: function(rsp, mode){
                if(mode=="success"){
                    this.$swal("Berhasil",rsp.data.message,mode);
                    if((this.records.current_page == this.records.last_page) || this.isEditing){
                        this.getRecords(this.records.current_page);
                    }
                    this.isLoading = false;
                }else{
                    //console.log(rsp.response);
                    this.error = rsp.response.data.error;
                    if(rsp.response.status == 401)
                        this.$swal(rsp.response.statusText, "Silahkan Login Aplikasi", mode);
                    else if(rsp.response.status == 403)
                        this.$swal(rsp.response.statusText, "Anda tidak memiliki akses melakukan operasi ini", mode);
                    else if(rsp.response.status == 404)
                        this.$swal(rsp.response.statusText, "Data / Halaman yang anda cari tidak ada", mode);
                    else if(rsp.response.status == 499)
                        this.$swal(rsp.response.statusText, rsp.response.data.message, mode);
                    else if(rsp.response.status == 428)
                        this.$swal(rsp.response.statusText, "Wajib mengisi Form", mode);
                    else if(rsp.response.status == 422) 
                        this.$modal.show('modal_tambah');
                    else
                        this.$swal("Gagal", "Terdapat kesalahan. Refresh halaman, masih error, hubungi Admin", mode);
                    
                    this.isLoading = false;
                }
            },
            nameWithNumbCoor: function ({ coordinator_numb, name }) {
              return coordinator_numb + ' - ' + name;
            },
            nameWithNumbSpons: function ({ sponsor_numb, name }) {
              return sponsor_numb + ' - ' + name;
            }
        },
        computed:{
            isFormError: function(){
                var i = 0;
                for(var er in this.error){
                    i++;
                }
                if(i>0) return true; else return false;
            }
        }
    }
    /*
    ,
      onNotaChange(e){
        this.img = e.target.files[0];
      },
      changeNota()
      {
        var fd = new FormData();
        fd.append('img',this.img);
        fd.append('idgambar',this.jadwal.kode_booking);
        if(this.img != '')
        {
          axios.post(this.$store.state.apiUrl + 'z_konsultasi/do_upload/', fd).then(response=>{
            alert(response.data.pesan);
            $("#modal-default2").modal('hide');
          });
        }
        else
        {
          alert("Masukkan gambar dengan benar.");
        }
      }
    */
</script>

<style scoped>
.err {
    color: red;
}
</style>