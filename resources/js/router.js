import Vue from 'vue';
import VueRouter from 'vue-router';

import PictureList from './components/pages/PictureList';
import PictureDetail from './components/pages/PictureDetail';
import Login from './components/pages/Login';
import SystemError from './components/pages/errors/System';
import NotFound from './components/pages/errors/NotFound';

import store from './store';

Vue.use(VueRouter);

const router = new VueRouter({
  mode: 'history',
  // ページ遷移時にページ先頭を表示
  scrollBehavior() {
    return { x: 0, y: 0 }
  },
  routes: [
    {
      path: '/',
      component: PictureList,
      props: route => {
        const page = route.query.page;
        return {
          page: /^[1-9][0-9]*$/.test(page) ? page * 1 : 1
        }
      }
    },
    {
      path: '/pictures/:id',
      name: 'pictureDetail',
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
    },
    {
      path: '*',
      component: NotFound
    }
  ]
});

export default router;
