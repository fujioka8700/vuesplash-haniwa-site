<template>
  <div
    v-if="picture"
    class="photo-detail"
    :class="{ 'photo-detail--column': fullWidth }"
  >
    <figure
      class="photo-detail__pane photo-detail__image"
      @click="fullWidth = ! fullWidth"
    >
      <img :src="picture.url" alt="">
      <figcaption>Posted by {{ picture.owner.name }}</figcaption>
    </figure>
    <div class="photo-detail__pane">
      <button class="button button--like" title="Like photo">
        <i class="icon ion-md-heart"></i>12
      </button>
      <a
        :href="`/pictures/${picture.id}/download`"
        class="button"
        title="Download picture"
      >
        <i class="icon ion-md-arrow-round-down"></i>Download
      </a>
      <h2 class="photo-detail__title">
        <i class="icon ion-md-chatboxes"></i>Comments
      </h2>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import { OK } from '../../util';

export default {
  props: {
    id: {
      type: String,
      required: true
    }
  },
  data() {
    return {
      picture: null,
      fullWidth: false
    }
  },
  methods: {
    async fetchPicture() {
      const response = await axios.get(`/api/pictures/${this.id}`);

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status);
        return false;
      }

      this.picture = response.data;
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
};
</script>
