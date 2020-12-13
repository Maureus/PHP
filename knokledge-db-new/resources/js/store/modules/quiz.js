const state = {
    quiz: [],
};

const getters = {
    getQuiz: state => {
        return state.quiz;
    }
};

const actions = {
    saveQuiz({commit}, payload) {
        commit('setQuiz', payload);
    }
};

const mutations = {
    setQuiz(state, payload) {
        state.quiz = payload;
    }
};

export default {
    state,
    getters,
    actions,
    mutations
}
