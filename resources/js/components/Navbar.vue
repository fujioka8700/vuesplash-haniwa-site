<template>
  <header>
    <nav class="navbar">
      <div v-if="isLogin">
        <PictureForm v-model="showForm" />
      </div>
      <RouterLink class="navbar__brand" to="/">
        Vuesplash
      </RouterLink>
      <div class="navbar__menu">
        <div class="navbar__item" v-if="isLogin">
          <button class="button" @click="showForm = !showForm">
            <i class="icon ion-md-add"></i>
            Submit a photo
          </button>
        </div>
        <span class="navbar__item" v-if="isLogin">
          {{ username }}
        </span>
      </div>
      <div class="navbar__item">
        <RouterLink class="button button--link" to="/login">
          Login / Register
        </RouterLink>
      </div>
    </nav>
  </header>
</template>

<script>
import PictureForm from './PictureForm';

export default {
  components: {
    PictureForm
  },
  data() {
    return {
      showForm: false
    }
  },
  computed: {
    isLogin() {
      return this.$store.getters['auth/check'];
    },
    username() {
      return this.$store.getters['auth/username'];
    }
  }
}
</script>
