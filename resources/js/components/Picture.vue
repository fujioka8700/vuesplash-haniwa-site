<template>
  <div>
    <figure class="photo__wrapper">
      <img
      class="photo__image"
        :src="item.url"
        :alt="`Picture by ${item.owner.name}`"
      >
    </figure>
  </div>
</template>

<script>
export default {
  props: {
    item: {
      type: Object,
      required: true
    }
  }
}
</script>
