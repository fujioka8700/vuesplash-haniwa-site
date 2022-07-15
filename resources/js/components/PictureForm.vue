<template>
  <div v-show="value" class="photo-form">
    <h2 class="title">Submit a photo</h2>
    <div v-show="loading" class="panel">
      <Loader>Sending your picture...</Loader>
    </div>
    <form v-show="!loading" class="form" @submit.prevent="submit">
      <div v-if="errors" class="errors">
        <ul v-if="errors.picture">
          <li v-for="msg in errors.picture" :key="msg">{{ msg }}</li>
        </ul>
      </div>
      <input class="form__item" type="file" @change="onFilechange">
      <output v-if="preview">
        <img :src="preview" alt="">
      </output>
      <div class="form__button">
        <button type="submit" class="button button--inverse">submit</button>
      </div>
    </form>
  </div>
</template>

<script>
import Loader from './Loader'
import { CREATED, UNPROCESSABLE_ENTITY }from '../util';
import axios from "axios";

export default {
  components: {
    Loader
  },
  props: {
    value: {
      type: Boolean,
      required: true
    }
  },
  data() {
    return {
      loading: false,
      preview: null,
      picture: null,
      errors: null
    }
  },
  methods: {
    // フォームでファイルが選択されたら実行される
    onFilechange(event) {
      // 何も選択されていなかったら処理中断
      if(event.target.files === 0) {
        this.reset();
        return false;
      }

      // ファイルが画像でなかったら処理中断
      if(! event.target.files[0].type.match('image.*')) {
        this.reset();
        return false;
      }

      const reader = new FileReader();

      // ファイルを読み込み終わったタイミングで実行する処理
      reader.onload = e => {
        // previewに読み込み結果(データURL)を代入する
        this.preview = e.target.result;
      }

      // ファイルを読み込む
      reader.readAsDataURL(event.target.files[0]);

      // 選択中のファイルを格納する
      this.picture = event.target.files[0];

      // 前回、画像以外を選択していた場合のエラーを削除
      this.errors = null;
    },

    // 入力の欄の値、プレビュー表示、格納したファイルをクリアにするメソッド
    reset() {
      this.preview = '';
      this.picture = null;

      // this.$elはコンポーネントそのもののDOM要素を指す
      this.$el.querySelector('input[type="file"]').value = null;
    },

    // ファイルを送信する
    async submit() {
      // 画像送信中はSending画面を表示する
      this.loading = true;

      // Ajax でファイルを送るため、FormDta APIを使用
      const formData = new FormData();
      formData.append('picture', this.picture);
      const response = await axios.post('/api/pictures', formData);

      // 画像送信後は、Sending画面を消す
      this.loading = false;

      // 画像以外がアップロードされたら、エラー内容を表示する
      if (response.status === UNPROCESSABLE_ENTITY) {
        this.errors = response.data.errors;
        return false;
      }

      this.reset();

      // 親コンポーネントの showForm を false にする
      this.$emit('input', false);

      // error/code を変更する
      if (response.status !== CREATED) {
        this.$store.commit('error/setCode', response.status);
        return false;
      }

      // メッセージ登録と、メッセージを表示する時間を設定する
      this.$store.commit('message/setContent', {
        content: '写真が投稿されました！',
        timeout: 6000
      });

      // 投稿完了後に写真詳細ページに移動する
      this.$router.push({
        name: 'pictureDetail',
        params: {
          id: response.data.id
        }
      })
    }
  }
}
</script>
