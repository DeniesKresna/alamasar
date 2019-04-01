<script>
import Loading from 'vue-loading-overlay';
    import 'vue-loading-overlay/dist/vue-loading.css';
    export default{
        mounted(){
            this.getRecords();
        },
        data(){
            return{
                tableDescription: 'Pendidikan',
                tableName: 'education',
                records: {},
                recordPicked: {},
                recordSlot:{},
                isLoading: false,
                isEditing: false,
                search: '',
                rowCount: 10
            }
        },
        components: {
            Loading
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
            modifyRecord: function(){
                this.isLoading = true;
                this.$modal.hide('modal_tambah');
                var qs = require('qs');
                if(this.isEditing){                    
                    axios.patch(this.$store.state.apiUrl + this.tableName + '/' + this.recordSlot.id, qs.stringify({'postData': this.recordSlot})).then(response=>{
                        this.ajaxResponse(response,'success');
                    }).catch(error => {
                        console.log(error.response);
                        this.ajaxResponse(error,'error');
                    });
                }else{
                    axios.post(this.$store.state.apiUrl + this.tableName, qs.stringify({'postData': this.recordSlot})).then(response=>{
                        this.ajaxResponse(response,'success');
                    }).catch(error => {
                        console.log(error.response);
                        this.ajaxResponse(error,'error');
                    });
                }
            },
            deleteRecord: function(rcd){
                var statusPred = "Aktifkan";
                var stringPred = "Aktifkan data berpengaruh terhadap data di tabel lain";
                if(rcd.isActive == 1){
                    statusPred = "Hapus data?";
                    stringPred = "Menghapus data dapat dilakukan jika tabel lain tidak menggunakan data ini";
                }
                this.$swal({
                  title: statusPred,
                  text: stringPred,
                  icon: "warning",
                  buttons: true,
                  dangerMode: true,
                })
                .then((willDelete) => {
                  if (willDelete) {
                    axios.delete(this.$store.state.apiUrl + this.tableName + '/' + rcd.id).then(response=>{
                        this.ajaxResponse(response,'success');
                    }).catch(error => {
                        console.log(error.response);
                        this.ajaxResponse(error,'error');
                    });
                  } else {
                    this.$swal("Dibatalkan");
                  }
                });/*
                var statusPred = "Aktifkan";
                var stringPred = "Aktifkan data berpengaruh terhadap data di tabel lain";
                if(rcd.isActive == 1){
                    statusPred = "Non-aktifkan data?";
                    stringPred = "Non-aktifkan data tidak menghapus data, tapi data sudah tidak dapat digunakan";
                }
                this.$swal({
                  title: statusPred,
                  text: stringPred,
                  icon: "warning",
                  buttons: true,
                  dangerMode: true,
                })
                .then((willDelete) => {
                  if (willDelete) {
                    axios.patch(this.$store.state.apiUrl + this.tableName + '/aktif/' + rcd.id).then(response=>{
                        this.ajaxResponse(response,'success');
                    }).catch(error => {
                        console.log(error.response);
                        this.ajaxResponse(error,'error');
                    });
                  } else {
                    this.$swal("Dibatalkan");
                  }
                });*/
            },          
            openModal: function(mode, rcd){
                this.isEditing = mode;
                if(this.isEditing){
                    this.recordSlot = JSON.parse(JSON.stringify(rcd));
                }
                else {
                    this.recordSlot = {};
                }
                this.$modal.show('modal_tambah');
                //$("#modal_tambah").modal("show");
            },
            resetGetRecords: function(){
                this.search = '';
                this.getRecords();
            },
            closeModal: function(){
                this.$modal.hide('modal_tambah');
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
            }
        }
    }
</script>

<style scoped>
.err {
    color: red;
}
</style>