import Home from './components/Home';
import About from "./components/About";
import NotFound from "./components/NotFound";
import Login from "./components/Login";
import Register from "./components/Register";
import Dashboard from "./components/Dashboard";
import Rand from "./components/Rand";
import Modal from "./components/Modal";
import Vue from 'vue';
import VueRouter from "vue-router";
import UserList from "./components/UserList";
import UserProfile from "./components/UserProfile";
import store from './store';

Vue.use(VueRouter);

export default new VueRouter({
    model: 'history',
    mode: 'history',
    linkActiveClass: 'font-semibold',
    routes: [
        {
            path: '*',
            component: NotFound,
        },
        {
            path: '/',
            component: Home,
            name: 'Home'
        },
        {
            path: '/about',
            component: About
        },
        {
            path: '/register',
            component: Register,
        },
        {
            path: '/login',
            component: Login,
            name: 'Login'
        },
        {
            path: '/dashboard',
            component: Dashboard,
            name: 'Dashboard',
            beforeEnter: (to, from, next) => {
                if (store.getters.getUser === null) {
                    next({
                        path: '/login'
                    });
                } else {
                    next();
                }
            }
        },
        {
            path: '/rand/:user_id',
            component: Rand,
        },
        {
            path: '/modal',
            component: Modal,
            name: 'Modal'
        },
        {
            path: '/dashboard/userlist',
            component: UserList,
            name: 'UserList'
        },
        {
            path: '/profile',
            component: UserProfile,
            name: 'Profile',
            beforeEnter: (to, from, next) => {
                if (store.getters.getUser === null) {
                    next({
                        path: '/login'
                    });
                } else {
                    next();
                }
            }
        }
    ]

})
