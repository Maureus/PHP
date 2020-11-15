const state = {
    user: null,
    admin: 'admin',
    teacher: 'teacher',
    student: 'student',
    errors: null,
    showModalConfirm: false,
};

const getters = {
    getUser: state => {
        return state.user;
    },
    getErrors: state => {
        return state.errors;
    },
    getShowModalConfirm: state => {
        return state.showModalConfirm;
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

    },
    async registerUser({commit}, user) {
        axios.post('/api/register', user).catch( error => {
            commit('setErrors', error);
        })
    },
    async saveErrors({commit}, errors) {
        commit('setErrors', errors);
    },
    confirm({commit}) {
        commit('setShowModalConfirm');
    }
};
const mutations = {
    setUser(state, payload) {
        state.user = payload;
    },
    setErrors(state, payload) {
        state.errors = payload;
    },
    setShowModalConfirm(state) {
        state.showModalConfirm = !state.showModalConfirm;
    }
};

export default {
    // namespaced: true,
    state,
    getters,
    actions,
    mutations
}
