import Vue from 'vue';
import VueRouter from 'vue-router';

import PictureList from './components/pages/PictureList';
import Login from './components/pages/Login';

import store from './store';

Vue.use(VueRouter);

const router = new VueRouter({
  mode: 'history',
  routes: [
    {
      path: '/',
      component: PictureList
    },
    {
      path: '/login',
      component: Login,
      beforeEnter(to, from, next) {
        if (store.getters['auth/check']) {
          next('/');
        } else {
          next();
        }
      }
    }
  ]
});

export default router;
