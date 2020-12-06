import Vue from 'vue';
import Vuex from 'vuex';

import currentUser from "./modules/currentUser";
import courses from "./modules/courses";

Vue.use(Vuex);


export default new Vuex.Store({
    modules: {
        currentUser,
        courses
    }
})
