import Vue from 'vue';
import App from './App.vue';
import router from './router/index';
import './registerServiceWorker';
import ArgonDashboard from './plugins/argon-dashboard';
import axios from 'axios';
import VueAxios from 'vue-axios';
import store from './store';
import authToken from './utils/token'
import VueIziToast from 'vue-izitoast';
import 'izitoast/dist/css/iziToast.min.css';
import i18n from './utils/i18n';
import { BootstrapVue, BootstrapVueIcons } from 'bootstrap-vue'
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-vue/dist/bootstrap-vue.css';
import Multiselect from 'vue-multiselect';
import Vuetify from 'vuetify';
import Firebase from 'firebase';
import { ValidationObserver, ValidationProvider  } from "vee-validate";
import { loadVeeValidate } from './utils/custom-veevalidate';
import Treeselect from '@riophae/vue-treeselect';
import '@riophae/vue-treeselect/dist/vue-treeselect.css'

loadVeeValidate();
Vue.component('ValidationProvider', ValidationProvider);
Vue.component('ValidationObserver', ValidationObserver);
Vue.use(BootstrapVue);
Vue.config.productionTip = false
Vue.use(VueAxios, axios);
Vue.use(ArgonDashboard);
Vue.use(VueIziToast);
Vue.component('multiselect', Multiselect)
Vue.component('Treeselect', Treeselect)
Vue.use(Multiselect);
Vue.use(Firebase);
Vue.use(BootstrapVueIcons)


router.beforeEach((to, from, next) => {
  if (to.matched.some(record => record.meta.requiresVisitor)) {
    if (authToken.getToken()) {
      next({
        path: '/'
      });
    } else {
      next();
    }
  } else if (to.matched.some(record => record.meta.requiresAuth)) {
    if (!authToken.getToken()) {
      next({
        path: '/login'
      });
    } else {
      store.dispatch('auth/getAuth')
        .then(() => {
          next();
        })
        .catch(() => {
          next('/login');
        });
    }
  } else {
    next();
  }
});
var firebaseConfig = {
  apiKey: "AIzaSyDrdz2moBxeyJE8xcIxpZ7JZTOmWRsIBt0",
  authDomain: "fabbi-training.firebaseapp.com",
  databaseURL: "https://fabbi-training.firebaseio.com",
  projectId: "fabbi-training",
  storageBucket: "fabbi-training.appspot.com",
  messagingSenderId: "647417507541",
  appId: "1:647417507541:web:89b5bbc34dcfd7982e3c5c",
  measurementId: "G-77ZTHVZ6GN"
};
Firebase.initializeApp(firebaseConfig);
new Vue({
  router,
  store,
  i18n,
  Vuetify,
  render: h => h(App)
}).$mount('#app')
