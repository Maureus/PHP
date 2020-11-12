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
    }
};
const actions = {
    async loginUser({state, commit}, user){
         await axios.get('/sanctum/csrf-cookie').then(async res => {
             await axios.post('/api/login', user).then(() =>{
                //     // localStorage.setItem(
                //     //     "kdb_token",
                //     //     res.data.token
                //     // )
                //     // window.location.replace("/dashboard");
            }).catch((error) =>{
                state.errors = error.response.data;
            });
        });
    },
    async getLoggedInUser({state, commit}){
        const response = await axios.get('/api/user')
            .catch((error) =>{
            state.errors = error.response.data;
        });
        commit('setUser', response.data);
    },
    logoutUser(){
        axios.post('/api/logout').then(()=>{
            state.user = {};
        }).catch((error) =>{
            state.errors = error.response.data;
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
