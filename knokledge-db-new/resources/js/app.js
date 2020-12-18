require('./bootstrap');

window.Vue = require('vue');

import VueRouter from "vue-router";
import store from './store';
import router from "./router";

import "@fortawesome/fontawesome-free/css/all.css";
import "@fortawesome/fontawesome-free/js/all.js";

Vue.use(VueRouter);

Vue.component('navbar-component', require('./components/NavBar.vue').default);
Vue.component('welcome-component', require('./components/Welcome').default);
Vue.component('user-list-item', require('./components/usersDir/UserListItem').default);
Vue.component('user-list', require('./components/usersDir/UserList').default);


router.beforeEach(async (to, from, next) => {
    await store.dispatch('preloaderTrue');
    await store.dispatch('getLoggedInUser')
        .then(() => {
                store.dispatch('preloaderFalse');
                next();
            }
        ).catch(() => {
            store.dispatch('preloaderFalse');
            next();
        });
});


const app = new Vue({
    el: '#app',
    router,
    store
});
