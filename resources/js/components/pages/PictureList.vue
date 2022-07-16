<template>
  <div class="photo-list">
    <h1>Picture List</h1>
    <div class="grid">
      <Picture
        class="grid__item"
        v-for="picture in pictures"
        :key="picture.id"
        :item='picture'
      />
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import { OK } from '../../util';
import Picture from '../Picture';

export default {
  components: {
    Picture
  },
  data() {
    return {
      pictures: []
    }
  },
  methods: {
    async fetchPicture() {
      const response =  await axios.get('/api/pictures');

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status);
        return false;
      }

      this.pictures = response.data.data;
    }
  },
  watch: {
    $route: {
      handler() {
        this.fetchPicture();
      },
      immediate: true
    }
  }
}
</script>
