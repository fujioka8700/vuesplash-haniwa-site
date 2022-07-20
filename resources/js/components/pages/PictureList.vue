<template>
  <div class="photo-list">
    <div class="grid">
      <Picture
        class="grid__item"
        v-for="picture in pictures"
        :key="picture.id"
        :item='picture'
        @like="onLikeClick"
      />
    </div>
    <Pagination
      :current-page="currentpage"
      :last-page="lastpage"
    />
  </div>
</template>

<script>
import axios from 'axios';
import { OK } from '../../util';
import Picture from '../Picture';
import Pagination from '../Pagination';

export default {
  components: {
    Picture,
    Pagination
  },
  props: {
    page: {
      type: Number,
      required: false,
      default: 1
    }
  },
  data() {
    return {
      pictures: [],
      currentpage: 0,
      lastpage: 0
    }
  },
  methods: {
    async fetchPicture() {
      const response =  await axios.get(`/api/pictures?page=${this.page}`);

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status);
        return false;
      }

      this.pictures = response.data.data;
      this.currentpage = response.data.current_page;
      this.lastpage = response.data.last_page;
    },
    async like(id) {
      const response = await axios.put(`/api/pictures/${id}/like`);

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status);
        return false;
      }

      this.pictures = this.pictures.map(picture => {
        if (picture.id === response.data.picture_id) {
          picture.likes_count += 1;
          picture.liked_by_user = true;
        }
        return picture;
      })
    },
    async unlike(id) {
      const response = await axios.delete(`/api/pictures/${id}/like`);

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status);
        return false;
      }

      this.pictures = this.pictures.map(picture => {
        if (picture.id === response.data.picture_id) {
          picture.likes_count -= 1;
          picture.liked_by_user = false;
        }
        return picture;
      })
    },
    onLikeClick({id, liked}) {
      if (! this.$store.getters['auth/check']) {
        alert('いいね機能を使うにはログインしてください');
        return false;
      }

      if (liked) {
        this.unlike(id);
      } else {
        this.like(id);
      }
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
