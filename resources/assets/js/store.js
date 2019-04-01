import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'
Vue.use(Vuex)
const state = {
	apiUrl: 'http://localhost/alamasar/public/',
	//apiUrl: 'http://alamaksara.org/dashboard/public/',
	formdata: {}
}
const getters = {	
	formdata: (state, getters) => {
		return state.formdata;
	}
}
const mutations = {
	setFormData: (state,payload) => {
		state.formdata = {};
		var params = new URLSearchParams();
		for (prop in payload.kelasdata){
			params.append(prop, payload.kelasdata.prop);
			console.log(payload.kelasdata.prop);
		}
		state.formdata = params;
	}
}
const actions = {
   	/*INIT_LOGIN: ({commit, state}) => {
   		axios.get(state.apiUrl + 'restdata/user').then(response=>{
   			state.sesi.id = response.data.user.id;
			state.sesi.nama = response.data.user.name;
			state.sesi.nickname = response.data.user.nickname;
			state.sesi.email = response.data.user.email;
   			var id = response.data.user.id;
   			axios.get(state.apiUrl + 'role/user/'+id).then(response=>{
	          	state.sesi.roles = response.data.role;
	          	//console.log(response.data.role);
	          	var datarole = new FormData();
	          	datarole.append('roles',response.data.role);
	          	axios.post(state.apiUrl + 'setting/hak',datarole).then(response=>{
	          		state.sesi.berhak = response.data.berhak;
	          	});
	        });
   		}).catch((error)=>{
   			state.isLogIn = false;
   		});
   	},*/
}
export default new Vuex.Store({
    state,
    getters,
    mutations,
    actions
})