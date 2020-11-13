const state = {
    user: null,
    admin: 'admin',
    teacher: 'teacher',
    student: 'student',
    errors: null,
};

const getters = {
    getUser: state => {
        return state.user;
    },
    getErrors: state => {
        return state.errors;
    }
};
const actions = {
    async loginUser({state, commit}, user){
         await axios.get('/sanctum/csrf-cookie').then(async () => {
             await axios.post('/api/login', user).then(() =>{
            }).catch((error) =>{
                 commit('setErrors', error);
             });
        });
    },
    async getLoggedInUser({commit}){
        const response = await axios.get('/api/user')
            .catch((error) =>{
                commit('setErrors', error);
        });
        commit('setUser', response.data);
    },
    async logoutUser({commit}){
        await axios.post('/api/logout').then(()=>{
            commit('setUser', null);
        }).catch((error) =>{
            commit('setErrors', error);
        });

    }
};
const mutations = {
    setUser(state, payload) {
        state.user = payload;
    },
    setErrors(state, payload) {
        state.errors = payload;
    },
};

export default {
    // namespaced: true,
    state,
    getters,
    actions,
    mutations
}
