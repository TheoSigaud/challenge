<template>
  <div :class="`notification is-${type}`">
    <button class="delete" @click="hideNotification"></button>
    <h4 class="title is-4">{{ title }}</h4>
    <p>{{ message }}</p>
  </div>
</template>

<script>
export default {
  name: 'Notification',
  props: ['title', 'message', 'type'],
  methods: {
    hideNotification() {
      this.$emit('hideNotification')
    }
  }
}
</script>
