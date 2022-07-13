import Vue from 'vue';
import VueRouter from 'vue-router';

import PictureList from './components/pages/PictureList';
import PictureDetail from './components/pages/PictureDetail';
import Login from './components/pages/Login';
import SystemError from './components/pages/errors/System';

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
      path: '/pictures/:id',
      name: 'PictureDetail',
      component: PictureDetail,
      props: true
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
    },
    {
      path: '/500',
      component: SystemError
    }
  ]
});

export default router;
