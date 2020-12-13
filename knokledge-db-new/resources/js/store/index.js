import Vue from 'vue';
import Vuex from 'vuex';

import currentUser from "./modules/currentUser";
import subjects from "./modules/subjects";
import roles from "./modules/roles";

Vue.use(Vuex);


export default new Vuex.Store({
    modules: {
        currentUser,
        subjects,
        roles
    }
})
