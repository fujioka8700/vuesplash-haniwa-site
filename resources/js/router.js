import Vue from 'vue';
import VueRouter from 'vue-router';

import PictureList from './components/pages/PictureList';
import Login from './components/pages/Login';

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
      component: Login
    }
  ]
});

export default router;
