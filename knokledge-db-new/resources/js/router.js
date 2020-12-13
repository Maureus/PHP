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
import Subjects from "./components/subjectsDir/Subjects";
import MySubjects from "./components/subjectsDir/MySubjects";
import SubjectContent from "./components/subjectsDir/SubjectContent";
import Quiz from "./components/quizzesDir/Quiz";


Vue.use(VueRouter);


export default new VueRouter({
    model: 'history',
    mode: 'history',
    linkActiveClass: 'font-semibold',
    scrollBehavior(to, from, savedPosition) {
        return {x: 0, y: 0}
    },
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
            name: 'Register',
            beforeEnter: (to, from, next) => {
                if (store.getters.getUser === null) {
                    next();
                } else {
                    next({
                        path: '/'
                    });
                }
            }
        },
        {
            path: '/login',
            component: Login,
            name: 'Login',
            beforeEnter: (to, from, next) => {
                if (store.getters.getUser === null) {
                    next();
                } else {
                    next({
                        path: '/'
                    });
                }
            }
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
            path: '/subjectlist',
            component: Subjects,
            name: 'Subjects'
        },
        {
            path: '/subject/:subject_id',
            component: SubjectContent,
            name: "SubjectContent"
        },
        {
            path: '/quiz/:quiz_id',
            component: Quiz,
            name: "Quiz"
        },
        {
            path: '/mysubjects',
            component: MySubjects,
            name: 'MySubjects',
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
            path: '/userlist',
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
