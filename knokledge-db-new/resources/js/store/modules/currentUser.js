const state = {
    user: null,
    errors: null,
    profileErrors: null,
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
    },
    getProfileErrors: state => {
        return state.profileErrors;
    }
};
const actions = {
    async loginUser({state, commit}, user) {
        await axios.get('/sanctum/csrf-cookie').then(async () => {
            await axios.post('/api/login', user).then(() => {
            }).catch((error) => {
                commit('setErrors', error);
            });
        });
    },
    async getLoggedInUser({commit}) {
        const response = await axios.get('/api/user')
            .catch(async (error) => {
                commit('setErrors', error);
            });
        commit('setUser', response.data);
    },
    async logoutUser({commit}) {
        await axios.post('/api/logout').then(() => {
            commit('setUser', null);
        }).catch((error) => {
            commit('setErrors', error);
        });

    },
    async registerUser({commit}, user) {
        axios.post('/api/register', user).catch(error => {
            commit('setErrors', error);
        })
    },
    async saveErrors({commit}, errors) {
        commit('setErrors', errors);
    },
    async saveProfileErrors({commit}, errors) {
        commit('setProfileErrors', errors);
    },
    confirm({commit}) {
        $('#myModal').modal('show');
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
    },
    setProfileErrors(state, payload) {
        state.errors = payload;
    }
};

export default {
    state,
    getters,
    actions,
    mutations
}
