<script>
import {FormWizard, TabContent} from 'vue-form-wizard'
import 'vue-form-wizard/dist/vue-form-wizard.min.css'
import Loading from 'vue-loading-overlay';
    import 'vue-loading-overlay/dist/vue-loading.css';
export default{
    components: {
      FormWizard, TabContent, Loading
      //, Datepicker
    },
    data(){
        return{
            postData: {
                student_numb: '',
                id_numb: '',
                card_id: 1,
                name: '',
                sex: 'P',
                address: '',
                sex: "L",
                domicile: '',
                birth_place: '',
                card_id: "",
                education_id: "",
                religion_id: "",
                family: [],
                birth_date: '2000-01-01',
                religion_id: '',
                status: 'current',
                contact: '',
                email: '',
                education_id: '',
                education_desc: '',
                account_numb: '',
                account_name: '',
                bank_name: '',
                bank_branch: '',
                bank_city: '',
                cc_numb: ''
            },
            families: [],
            familySlot:{
                birth_date: '2000-01-01'
            },
            scores: [],
            nilaiSlot:{
                type: 'nb',
                year: '',
                score: 0
            },
            eduNeeds: [],
            eduNeedSlot:{
                eduNeed: '',
                price: 0
            },
            isLoading: false,
            isEditing: false,
            isNilaiEditing: false,
            isEduNeedEditing: false,
            isStudentEditing: false,
            student_id: 0,
            wizardTitle: "Formulir tambah siswa"
        }
    },
    mounted(){
            var segment = (window.location.href).split("/");
            if(isNaN(segment[segment.length-1])){
                this.student_id=0;
                //this.inputSementara();
            }
            else{
                this.student_id = segment[segment.length-1];
                this.isStudentEditing = true;
                this.wizardTitle = "Formulir edit siswa"
                this.getRecords();
            }
    },
    methods: {
        getRecords: function(){
            this.isLoading = true;
            axios.get(this.$store.state.apiUrl + 'student_family/' + this.student_id).then(response=>{
                this.postData = response.data.student;
                this.families = response.data.families;
                this.scores = response.data.scores;
                this.eduNeeds = response.data.eduNeeds;
                this.isLoading = false;
            }).catch(error => {
                this.ajaxResponse(error,'error');
                this.isLoading = false;
            });
        },
        modifyRecord: function(){
            this.$validator.validate().then(result =>{
                if(!result){
                    this.$swal("Perhatian", "Isi dahulu data siswa dengan benar sebelum menyelesaikan formulir", "warning");
                }
                else{
                    this.isLoading = true;
                    var qs = require('qs');
                    if(!this.isStudentEditing){
                        axios.post(this.$store.state.apiUrl + 'student', qs.stringify({'postData': this.postData, 'familiesData': this.families, 'scoresData': this.scores, 'eduNeedsData': this.eduNeeds})).then(response=>{
                            console.log(response);
                            this.ajaxResponse(response,'success');
                        }).catch(error => {
                            console.log(error.response);
                            this.ajaxResponse(error,'error');
                        });
                    }
                    else{
                        axios.patch(this.$store.state.apiUrl + 'student/' + this.student_id, qs.stringify({'postData': this.postData, 'familiesData': this.families, 'scoresData': this.scores, 'eduNeedsData': this.eduNeeds})).then(response=>{
                            console.log(response);
                            this.ajaxResponse(response,'success');
                        }).catch(error => {
                            console.log(error.response);
                            this.ajaxResponse(error,'error');
                        });
                    }
                }
            });
        },
        deleteRecord: function(rcd){
            for(var i=0;i<this.families.length;i++){
                    if(this.families[i].nik == rcd.nik){
                        this.families.splice(i,1);
                        break;
                    }
                }
        },          
        openModal: function(mode, rcd){
            this.$validator.validate().then(result =>{
                if(!result){
                    this.$swal("Perhatian", "Isi dahulu data siswa dengan benar sebelum menambah keluarga", "warning");
                }
                else{
                    this.isEditing = mode;
                    if(this.isEditing){
                        this.familySlot = JSON.parse(JSON.stringify(rcd));
                    }
                    else {
                        this.familySlot = {
                            education_id: "",
                            nation_id: "",
                            religion_id: "",
                            occupation_id: "",
                            occupation_desc: "",
                            type: "",
                            isDied: 0,
                            marital_status: "",
                            birth_date: '2000-01-01'
                        };
                    }
                    this.$modal.show('modal_tambah');
                }
            });
        },
        openScoreModal: function(mode, rcd){
            this.$validator.validate().then(result =>{
                if(!result){
                    this.$swal("Perhatian", "Isi dahulu data siswa dan keluarga dengan benar sebelum menambah nilai", "warning");
                }
                else{
                    this.isNilaiEditing = mode;
                    if(this.isNilaiEditing){
                        this.nilaiSlot = JSON.parse(JSON.stringify(rcd));
                    }
                    else {
                        this.nilaiSlot = {
                            type: 'nb',
                            year: '',
                            score: 0
                        };
                    }
                    this.$modal.show('modal_nilai');
                }
            });
        },
        deleteScoreRecord: function(rcd){
            for(var i=0;i<this.scores.length;i++){
                    if(this.scores[i].year_id == rcd.year_id){
                        this.scores.splice(i,1);
                        break;
                    }
                }
        },
        modifyNilai: function(){
                this.$validator.validate().then(result =>{
                    if(result){
                        if(!this.isNilaiEditing){
                            this.scores.push(this.nilaiSlot);
                        }
                        else{
                            for(var i=0;i<this.scores.length;i++){
                               if(this.scores[i].year_id == this.nilaiSlot.year_id){
                                    this.scores.splice(i,1,this.nilaiSlot);
                                    break;
                                }
                            }
                        }
                        this.closeModal();
                    }
                });
                
        }, 
        modifyEduNeed: function(){
                this.$validator.validate().then(result =>{
                    if(result){
                        if(!this.isEduNeedEditing){
                            this.eduNeeds.push(this.eduNeedSlot);
                        }
                        else{
                            for(var i=0;i<this.eduNeeds.length;i++){
                               if(this.eduNeeds[i].eduNeed == this.eduNeedSlot.eduNeed){
                                    this.eduNeeds.splice(i,1,this.eduNeedSlot);
                                    break;
                                }
                            }
                        }
                        this.closeModal();
                    }
                });
                
        }, 
        openEduNeedModal: function(mode, rcd){
            this.$validator.validate().then(result =>{
                if(!result){
                    this.$swal("Perhatian", "Isi dahulu data siswa dengan benar sebelum menambah kebutuhan", "warning");
                }
                else{
                    this.isEduNeedEditing = mode;
                    if(this.isEduNeedEditing){
                        this.eduNeedSlot = JSON.parse(JSON.stringify(rcd));
                    }
                    else {
                        this.eduNeedSlot = {
                            eduNeed: '',
                            price: 0
                        };
                    }
                    this.$modal.show('modal_eduneed');
                }
            });
        },
        deleteEduNeedRecord: function(rcd){
            for(var i=0;i<this.eduNeeds.length;i++){
                    if(this.eduNeeds[i].eduNeed == rcd.eduNeed){
                        this.eduNeeds.splice(i,1);
                        break;
                    }
                }
        },
        closeModal: function(){
            this.$modal.hide('modal_tambah');
            this.$modal.hide('modal_nilai');
            this.$modal.hide('modal_eduneed');
        },
        getFamilyData: function(nik){
            this.isLoading = true;
            axios.get(this.$store.state.apiUrl + 'family/' + nik).then(response=>{
                this.families = response.data.records;
                this.isLoading = false;
            }).catch(error=>{
                console.log(error.response);
                this.isLoading = false;
            });
        },
        modifyFamily: function(){
                this.$validator.validate().then(result =>{
                    if(result){
                        if(!this.isEditing){
                            this.families.push(this.familySlot);
                        }
                        else{
                            for(var i=0;i<this.families.length;i++){
                               if(this.families[i].nik == this.familySlot.nik){
                                    this.families.splice(i,1,this.familySlot);
                                    break;
                                }
                            }
                        }
                        this.closeModal();
                    }
                });
                
        },
        checkFamilyExist: function(){
            this.isLoading = true;
            axios.get(this.$store.state.apiUrl + 'family/nik/' + this.familySlot.nik).then(response=>{
                this.familySlot = response.data.record;
                console.log(this.familySlot);
                    this.isLoading = false;
            }).catch(error => {
                if(error.response.status == 404){
                    this.isLoading = false;
                }
                else{
                    console.log(error.response);
                    this.ajaxResponse(error,'error');
                }
            });
        },
        ajaxResponse: function(rsp, mode){
                if(mode=="success"){
                    var url = this.$store.state.apiUrl;
                    this.isLoading = false;
                    if(!this.isStudentEditing){
                        this.$swal({
                            title: "Berhasil menambah Siswa",
                            text: "Apakah anda ingin memasukkan data siswa baru yang lain?",
                            icon: mode,
                            buttons:true,
                        }).then(addagain =>{
                            if(addagain)
                                window.location.href = url + 'student/add/page';
                            else
                                window.location.href = url + 'student/page';
                        });
                    }   
                    else{
                        this.$swal("Berhasil update Siswa", "Kembali ke Daftar Siswa", "success")
                            .then((value) => {
                                window.location.href = url + 'student/page';
                            });
                    }
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
            },
            inputSementara: function(){
                this.postData={
                    student_numb: '12345',
                    id_numb: '123456789',
                    card_id: 1,
                    name: 'Cynthia Cecilia',
                    sex: 'P',
                    address: 'Bangunjiwo Sejahtera Blok F 123, Kasihan Bantul',
                    sex: "L",
                    domicile: 'Apartemen Dian Regency 1526, Sukolilo',
                    birth_place: 'Surabaya',
                    card_id: "",
                    education_id: "",
                    religion_id: "",
                    family: [],
                    birth_date: '1995-03-09',
                    religion_id: 2,
                    contact: '08113235533',
                    email: 'cynthcecilia@gmail.com',
                    education_id: 4,
                    education_desc: 'S1 Ilmu Komunikasi UNITOMO',
                    account_numb: '03612345',
                    account_name: 'Cynthia Cecilia',
                    bank_name: 'BNI',
                    bank_branch: 'BNI Graha Pangeran',
                    bank_city: 'Surabaya',
                    cc_numb: 'OPS-902-K-8293-YL'
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