require('./bootstrap');

window.Vue = require('vue');

import VueRouter from "vue-router";
import routes from './routes';
import store from './store';


Vue.use(VueRouter);

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('navbar-component', require('./components/NavBar.vue').default);

const app = new Vue({
    el: '#app',
    router: new VueRouter(routes),
    store
});
