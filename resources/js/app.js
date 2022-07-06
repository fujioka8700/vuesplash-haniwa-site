require('./bootstrap');
import Vue from 'vue';

import router from './router';
import store from './store';
import App from './components/App';

const createApp = async function() {
  await store.dispatch('auth/currentUser');

  new Vue({
    el: '#app',
    router,
    store,
    components: {
      App
    },
    template: '<App />'
  });
}

createApp();
