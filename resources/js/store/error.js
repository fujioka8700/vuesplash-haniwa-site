const state = {
  code: null
};

const getters = {
  code: state => state.code
}

const mutations = {
  setCode(state, code) {
    state.code = code;
  }
};

export default {
  namespaced: true,
  state,
  getters,
  mutations
}
