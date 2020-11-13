require('./bootstrap');

window.Vue = require('vue');

import VueRouter from "vue-router";
import store from './store';
import router from "./router";


Vue.use(VueRouter);

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('navbar-component', require('./components/NavBar.vue').default);
Vue.component('welcome-component', require('./components/Welcome').default);

const app = new Vue({
    el: '#app',
    router,
    store
});
