<template>
  <div>
    <Navbar />
    <main>
      <div class="container">
        <Message />
        <RouterView />
      </div>
    </main>
    <Footer />
  </div>
</template>

<script>
import { NOT_FOUND, UNAUTHORIZED, INTERNAL_SERVER_ERROR } from '../util';
import Message from './Message';
import Navbar from './Navbar';
import Footer from './Footer';
import axios from 'axios';

export default {
  components: {
    Message,
    Navbar,
    Footer
  },
  computed: {
    errorCode() {
      return this.$store.state.error.code;
    }
  },
  watch: {
    errorCode: {
      async handler(val) {
        if(val === INTERNAL_SERVER_ERROR) {
          this.$router.push('/500');
        } else if (val === UNAUTHORIZED) {
          // トークンをリフレッシュ
          await axios.get('/api/reflesh-token');
          // ストアのuserをクリア
          this.$store.commit('auth/setUser', null);
          // ログイン画面へ
          this.$router.push('/login');
        } else if (val === NOT_FOUND) {
          this.$router.push('/not-found');
        }
      },
      immediate: true
    },
    $route() {
      this.$store.commit('error/setCode', null);
    }
  }
}
</script>
