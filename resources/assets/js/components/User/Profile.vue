<script>
import Loading from 'vue-loading-overlay';
    import 'vue-loading-overlay/dist/vue-loading.css';
export default{
    data(){
        return{
            postData: {
                name: '',
                email: '',
                sex: 'L',
                contact: '',
                level: ''

            },
            postPassword:{
                old_password: '',
                new_password: '',
                new_password2: '',
            },
            updatefototime: new Date().getTime(),
            image: '',
            user_id: 0,
            isLoading: false,
            wizardTitle: "Profil User"
        }
    },
        components: {
            Loading
        },
    mounted(){
            var segment = (window.location.href).split("/");
                this.user_id = segment[segment.length-1];
                this.isStudentEditing = true;
                this.wizardTitle = "Profile User";
                this.getRecord();
    },
    methods: {
        getRecord: function(){
            this.isLoading = true;
            axios.get(this.$store.state.apiUrl + 'user/profiledata/' + this.user_id).then(response=>{
                this.postData = response.data.record;
                this.isLoading = false;
            }).catch(error => {
                this.ajaxResponse(error,'error');
                this.isLoading = false;
            });
        },
        modifyRecord: function(){
            this.$validator.validate().then(result =>{
                if(!result){
                    this.$swal("Perhatian", "Isi dahulu data user dengan benar sebelum menyelesaikan formulir", "warning");
                }
                else{
                    this.isLoading = true;
                    var qs = require('qs');
                        axios.patch(this.$store.state.apiUrl + 'user/' + this.user_id, qs.stringify({'postData': this.postData})).then(response=>{
                            console.log(response);
                            this.ajaxResponse(response,'success');
                        }).catch(error => {
                            console.log(error.response);
                            this.ajaxResponse(error,'error');
                        });
                }
            });
        },
        modifyPassword: function(){
                if(this.postPassword.new_password != this.postPassword.new_password2 || this.postPassword.new_password == '' || this.postPassword.old_password == ''){
                    this.$swal("Perhatian", "Isi dahulu data password dengan benar sebelum menyelesaikan formulir", "warning");
                    this.postPassword.new_password = ''; this.postPassword.new_password2=''; this.postPassword.old_password = '';
                }
                else{
                    this.isLoading = true;
                    console.log(this.postPassword);
                    var qs = require('qs');
                    axios.post(this.$store.state.apiUrl + 'user/password', qs.stringify({'postData': this.postPassword})).then(response=>{
                        console.log(response);
                        this.postPassword.new_password = ''; this.postPassword.new_password2=''; this.postPassword.old_password = '';
                        this.ajaxResponse(response,'success');
                    }).catch(error => {
                        console.log(error.response);
                        this.postPassword.new_password = ''; this.postPassword.new_password2=''; this.postPassword.old_password = '';
                        this.$swal("Perhatian", error.response.data.msg, "error");
                        this.isLoading = false;
                    });
                }
        },
        closeModal: function(){
            this.$modal.hide('modal_dp');
        },
            openModalDp: function(){
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
                        axios.post(this.$store.state.apiUrl + 'user/dp/' + this.user_id, fd, {
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
                        alert("Masukan gambar dengan benar. (ukuran maks 512 k)");
                    }
                });
            },
        ajaxResponse: function(rsp, mode){
                if(mode=="success"){
                    this.isLoading = false;
                    this.$swal("Berhasil",rsp.data.message,mode);
                }else{
                    if(rsp.response.status == 401)
                        this.$swal(rsp.response.statusText, "Silahkan Login Aplikasi", mode);
                    else if(rsp.response.status == 403)
                        this.$swal(rsp.response.statusText, "Anda tidak memiliki akses melakukan operasi ini", mode);
                    else if(rsp.response.status == 404)
                        this.$swal(rsp.response.statusText, "Data / Halaman yang anda cari tidak ada", mode);
                    else if(rsp.response.status == 428 || rsp.response.status == 422)
                        this.$swal(rsp.response.statusText, rsp.response.data.message, mode);
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