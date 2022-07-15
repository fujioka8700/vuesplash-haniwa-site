const state = {
  content: ''
};

const mutations = {
  // 送信状態の表示
  setContent(state, { content, timeout }) {
    state.content = content;

    if (typeof timeout === 'undefined') {
      timeout = 3000;
    }

    setTimeout(() => {
      state.content = '';
    }, timeout);
  }
};

export default {
  namespaced: true,
  state,
  mutations
}
