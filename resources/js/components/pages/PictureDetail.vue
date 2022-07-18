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
      <ul v-if="picture.comments.length > 0" class="photo-detail__comments">
        <li
          v-for="comment in picture.comments"
          :key="comment.content"
          class="photo-detail__commentItem"
        >
          <p class="photo-detail__commentBody">
            {{ comment.content }}
          </p>
          <p class="photo-detail__commentInfo">
            {{ comment.author.name }}
          </p>
        </li>
      </ul>
      <p v-else>No comments yet.</p>
      <form class="form" v-if="isLogin" @submit.prevent="addComment">
        <div v-if="commentErrors" class="errors">
          <ul v-if="commentErrors.content">
            <li v-for="msg in commentErrors.content" :key="msg">{{ msg }}</li>
          </ul>
        </div>
        <textarea class="form__item" v-model="commentContext"></textarea>
        <div class="form_button">
          <button type="submit" class="button button--inverse">submit comment</button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import { mapGetters } from 'vuex';
import { OK, CREATED, UNPROCESSABLE_ENTITY } from '../../util';

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
      fullWidth: false,
      commentContext: '',
      commentErrors: null
    }
  },
  computed: {
    ...mapGetters({
      isLogin: 'auth/check'
    })
  },
  methods: {
    async fetchPicture() {
      const response = await axios.get(`/api/pictures/${this.id}`);

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status);
        return false;
      }

      this.picture = response.data;
    },
    async addComment() {
      const response = await axios.post(`/api/pictures/${this.id}/comments`, {
        content: this.commentContext
      });

      // バリデーションエラー
      if (response.status === UNPROCESSABLE_ENTITY) {
        this.commentErrors = response.data.errors;
        return false;
      }

      this.commentContext = '';

      // エラーメッセージをクリア
      this.commentErrors = null;

      // その他のエラー
      if (response.status !== CREATED) {
        this.$store.commit('error/setCode', response.status);
        return false;
      }

      // 投稿したコメントを表示する
      this.picture.comments = [
        response.data,
        ...this.picture.comments
      ];
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
