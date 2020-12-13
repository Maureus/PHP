const state = {
    preloader: false,
};

const getters = {
    getPreloader: state => {
        return state.preloader;
    }
};

const actions = {
    preloaderTrue({commit}) {
        commit('setPreloaderTrue');
    },
    preloaderFalse({commit}) {
        commit('setPreloaderFalse');
    },
};

const mutations = {
    setPreloaderTrue(state) {
        state.preloader = true;
    },
    setPreloaderFalse(state) {
        state.preloader = false;
    }
};

export default {
    state,
    getters,
    actions,
    mutations
}
