<template>
  <div :class="`notification is-${type}`">
    <button class="delete" @click="hide"></button>
    <h4 class="title is-4">{{ title }}</h4>
    <p>{{ message }}</p>
  </div>
</template>

<script>
export default {
  name: 'Notification',
  props: ['title', 'message', 'type'],
  methods: {
    hide() {
      this.$emit('hide')
    }
  }
}
</script>
