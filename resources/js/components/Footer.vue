<template>
  <footer class="footer">
    <button class="button button--link" @click="logout" v-if="isLogin">Logout</button>
    <RouterLink class="button button--link" to="/login">
      Login / Register
    </RouterLink>
  </footer>
</template>

<script>
export default {
  methods: {
    async logout() {
      await this.$store.dispatch('auth/logout');
    }
  },
  computed: {
    isLogin() {
      return this.$store.getters['auth/check'];
    }
  }
}
</script>
