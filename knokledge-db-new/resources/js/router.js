import Home from './components/Home';
import About from "./components/About";
import NotFound from "./components/NotFound";
import Login from "./components/Login";
import Register from "./components/Register";
import Dashboard from "./components/Dashboard";
import Rand from "./components/Rand";
import Vue from 'vue';
import VueRouter from "vue-router";
import UserList from "./components/usersDir/UserList";
import UserProfile from "./components/usersDir/UserProfile";
import store from './store';
import Subjects from "./components/coursesDir/Subjects";
import MySubjects from "./components/coursesDir/MySubjects";
import StudyMats from "./components/studyMats/StudyMats";

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
            name: 'Register'
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
            // call user preload
            beforeEnter: async (to, from, next) => {
                await store.dispatch('getLoggedInUser').catch(err => console.log(err));
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
            path: '/subjectlist',
            component: Subjects,
            name: 'Subjects'
        },
        {
            path: '/mysubjects',
            component: MySubjects,
            name: 'MySubjects',
            beforeEnter: async (to, from, next) => {
                await store.dispatch('getLoggedInUser').catch(err => console.log(err));
                if (store.getters.getUser === null) {
                    next({
                        path: 'Login'
                    });
                } else {
                    next();
                }
            }
        },
        {
            path: '/userlist',
            component: UserList,
            name: 'UserList'
        },
        {
            path: '/study_mats',
            component: StudyMats,
            name: 'StudyMats'
        },
        {
            path: '/profile',
            component: UserProfile,
            name: 'Profile',
            // call user preload
            beforeEnter: async (to, from, next) => {
                await store.dispatch('getLoggedInUser').catch(err => console.log(err));
                if (store.getters.getUser === null) {
                    next({
                        path: 'Login'
                    });
                } else {
                    next();
                }
            }
        }
    ]

})
