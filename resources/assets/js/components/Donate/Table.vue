<script>
import Multiselect from 'vue-multiselect';
import '../../filters.js';
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';
    export default{
        mounted(){
            axios.get(this.$store.state.apiUrl + 'coordinators_sponsors_students').then(response=>{
                this.isLoading = true;
                this.coordinators = response.data.coordinators;
                this.sponsors = response.data.sponsors;
                this.students = response.data.students;
                this.years = response.data.years;
                this.isLoading = false;
                this.getRecords();
            });
        },
        components: {
          Multiselect, Loading
        },
        data(){
            return{
                tableDescription: 'Donate',
                tableName: 'donate',
                records: {},
                recordPicked: {
                    coordinator: {},
                    sponsor: {},
                    student: {}
                },
                coordinators:[],
                sponsors:[],
                students:[],
                years:[],
                recordSlot:{
                    coordinator: {},
                    sponsor: {},
                    student: {},
                    amount: 0,
                    send_time: '2010-01-01',
                    isVerified: 1
                },
                isLoading: false,
                isEditing: false,
                search: '',
                rowCount: 10,
                image: '',
                updatefototime: new Date().getTime()
            }
        },
        methods: {
            getRecords: function(pg=1){
                this.isLoading = true;
                axios.get(this.$store.state.apiUrl + this.tableName + '?page=' + pg + '&search=' + this.search + '&rows=' + this.rowCount).then(response=>{
                    this.records = response.data.records;
                    this.isLoading = false;
                }).catch(error => {
                    console.log(error.response);
                    this.ajaxResponse(error,'error');
                });
            },
            showRecord: function(id){
                this.isLoading = true;
                axios.get(this.$store.state.apiUrl + this.tableName + '/' + id).then(response=>{
                    console.log(response);
                    this.recordPicked = response.data.record;
                    this.isLoading = false;
                    this.$modal.show('modal_lihat');
                }).catch(error=>{
                    console.log(error.response);
                    this.ajaxResponse(error,'error');
                });
            },
            openModal: function(mode, rcd){
                this.isEditing = mode;
                if(this.isEditing){
                    this.recordSlot = JSON.parse(JSON.stringify(rcd));
                }
                else {
                    this.recordSlot = {
                        coordinator: '',
                        sponsor: '',
                        student: '',
                        amount: 0,
                        send_time: '2010-01-01',
                        isVerified: 1
                    };
                }
                this.$modal.show('modal_tambah');
                //$("#modal_tambah").modal("show");
            },
            openModalDp: function(rcd){
                this.recordSlot = rcd;
                this.$modal.show('modal_dp');
            },
            getNota: function(e){
                this.image = e.target.files[0];
                console.log(this.image);
            },
            modifyDonate: function(){
                    if(typeof this.recordSlot.years != 'undefined'){
                        this.isLoading = true;
                        var fd = new FormData();
                        var arrayYear = this.recordSlot.years.map((a)=>{ return a.id; });
                        console.log(arrayYear);
                        fd.append('student_id',this.recordSlot.student.id);
                        fd.append('coordinator_id',this.recordSlot.coordinator.id);
                        fd.append('sponsor_id',this.recordSlot.sponsor.id);
                        fd.append('amount',this.recordSlot.amount);
                        fd.append('send_time',this.recordSlot.send_time);
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
                            //this.$swal("Berhasil",response.data.message ,"success");
                            this.closeModalTambah();
                            this.ajaxResponse(response,'success');
                        }).catch(error=>{
                            console.log(error.response);
                            this.ajaxResponse(error,'error');
                        });
                    }
                    else{
                        alert("Minimal 1 Tahun Ajaran");
                    }
            },
            openModalDp: function(rcd){
                this.recordSlot = rcd;
                this.$modal.show('modal_dp');
            },
            changeDp: function(){
                this.$validator.validate().then(result =>{
                    if(result){
                        this.isLoading = true;
                        var fd = new FormData();
                        fd.append('image',this.image);
                        axios.post(this.$store.state.apiUrl + this.tableName + '/dp/' + this.recordSlot.donate_id, fd, {
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
                    axios.delete(this.$store.state.apiUrl + 'donate/' + dnt.donate_id).then(response=>{
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
            resetGetRecords: function(){
                this.search = '';
                this.getRecords();
            },
            closeModalTambah: function(){
                this.$modal.hide('modal_tambah');
            },
            closeModalDp: function(){
                this.$modal.hide('modal_dp');
            },
            closeModalLihat: function(){
                this.$modal.hide('modal_lihat');
            },
            closeModal: function(){
                this.$modal.hide('modal_tambah');
                this.$modal.hide('modal_dp');
                this.$modal.hide('modal_lihat');
            },
            ajaxResponse: function(rsp, mode){
                if(mode=="success"){
                    this.$swal("Berhasil",rsp.data.message,mode);
                    if((this.records.current_page == this.records.last_page) || this.isEditing){
                        this.getRecords(this.records.current_page);
                    }
                    this.isLoading = false;
                }else{
                    if(rsp.response.status == 401)
                        this.$swal(rsp.response.statusText, "Silahkan Login Aplikasi", mode);
                    else if(rsp.response.status == 403)
                        this.$swal(rsp.response.statusText, "Anda tidak memiliki akses melakukan operasi ini", mode);
                    else if(rsp.response.status == 404)
                        this.$swal(rsp.response.statusText, "Data / Halaman yang anda cari tidak ada", mode);
                    else if(rsp.response.status == 499)
                        this.$swal("Gagal", rsp.response.data.message, mode);
                    else if(rsp.response.status == 428)
                        this.$swal(rsp.response.statusText, "Wajib mengisi Form", mode);
                    else if(rsp.response.status == 422) 
                        this.$modal.show('modal_tambah');
                    else
                        this.$swal("Gagal", "Terdapat kesalahan. Refresh halaman, masih error, hubungi Admin", mode);
                    
                    this.isLoading = false;
                }
            },
            nameWithNumbStud: function ({ student_numb, name }) {
              return student_numb + ' - ' + name;
            },
            nameWithNumbCoor: function ({ coordinator_numb, name }) {
              return coordinator_numb + ' - ' + name;
            },
            nameWithNumbSpons: function ({ sponsor_numb, name }) {
              return sponsor_numb + ' - ' + name;
            }
        }
    }
</script>

<style scoped>
.first-fit tr td:first-child{
    width:1%;
    white-space:nowrap;
}
</style>