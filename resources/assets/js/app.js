
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
var VueValidationIna = require('vee-validate/dist/locale/id');

import store from './store';
import VueSwal from 'vue-swal';
import vmodal from 'vue-js-modal';
import filters from './filters';   
import VueFormWizard from 'vue-form-wizard';
import 'vue-form-wizard/dist/vue-form-wizard.min.css';
import VeeValidate from 'vee-validate';

filters.forEach(f => {
   Vue.filter(f.name, f.execute)
})

Vue.use(VeeValidate, {
  locale: 'id',
  dictionary: {
    id: VueValidationIna
  }
});
Vue.use(VueSwal);
Vue.use(vmodal);
Vue.use(VueFormWizard) 
Vue.component('pagination', require('laravel-vue-pagination'));

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example-component', require('./components/ExampleComponent.vue'));
Vue.component('user-table', require('./components/User/Table.vue'));
Vue.component('student-add', require('./components/Student/Add.vue'));
Vue.component('student-table', require('./components/Student/Table.vue'));
Vue.component('religion-table', require('./components/Setting/Religion.vue'));
Vue.component('nation-table', require('./components/Setting/Nation.vue'));
Vue.component('occupation-table', require('./components/Setting/Occupation.vue'));
Vue.component('card-table', require('./components/Setting/Card.vue'));
Vue.component('education-table', require('./components/Setting/Education.vue'));
Vue.component('year-table', require('./components/Setting/Year.vue'));
Vue.component('coordinator-table', require('./components/Coordinator/Table.vue'));
Vue.component('sponsor-table', require('./components/Sponsor/Table.vue'));
Vue.component('donate-table', require('./components/Donate/Table.vue'));
Vue.component('user-profile-table', require('./components/User/Profile.vue'));

const app = new Vue({
    el: '#app',
    store
});
