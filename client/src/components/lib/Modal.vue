<script>
import {ref} from "vue";

export default {
  data() {
    return {
      selectedOption: '',
      options: [
        {value: null, text: 'Rating'},
        {value: '1', text: '1'},
        {value: '2', text: '2'},
        {value: '3', text: '3'},
        {value: '4', text: '4'},
        {value: '5', text: '5'}
      ]
    }
  },
  methods: {
    getSelectedValue() {
      console.log(this.selectedOption)
    }
  },
  setup() {
    const showModal = ref(false);

    return {
      showModal
    }
  }
}
</script>

<template>
  <button class="button is-info"  @click="showModal = true">
    <slot name="btnModal"></slot>
  </button>

  <div class="modal" style="display: block;" v-if="showModal">
    <div class="modal-background"></div>
    <div class="modal-card">

      <header class="modal-card-head">
        <p class="modal-card-title">Review for {{ ad_id }}</p>
        <button class="delete" aria-label="close" v-on:click="showModal = false"></button>
      </header>

      <section class="modal-card-body">
        <input class="input is-link" type="text" placeholder="Title">

        <div class="select">
          <select v-model="selectedOption" @change="getSelectedValue">
            <option v-for="option in options" :value="option.value">
              {{ option.text }}
            </option>
          </select>
        </div>

        <textarea class="textarea" placeholder="Leave a review here"></textarea>
      </section>

      <footer class="modal-card-foot">
        <button class="button is-success">
          <ion-icon name="send"></ion-icon>
          Send
        </button>
        <button class="button" v-on:click="showModal = false">Cancel</button>
      </footer>
    </div>
  </div>
</template>