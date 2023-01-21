<script>
import {ref} from "vue";

export default {
  props: ['ad_id', 'c_id'],
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

  setup(props){
    const titleReview = ref("")
    const descriptionReview = ref("")
    const rateReview = ref("")

    const onSubmit = async () => {
      try {
        const response = await fetch("https://localhost/comments", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({
            advertisement_id : props.ad_id,
            client_id: props.c_id,
            title: titleReview.value,
            message: descriptionReview.value,
            rate: parseFloat(rateReview.value),
            status : 0,
            created_at: new Date()
          })
        });
        if (!response.ok) {
          throw new Error("An error occurred while submitting the form");
        }
        alert("Form submitted successfully!");
      } catch (error) {
        console.error(error);
        alert("An error occurred while submitting the form");
      }
    }

    return {
      titleReview,
      descriptionReview,
      rateReview,
      onSubmit
    }
  }

}
</script>
<template>
  <form @submit.prevent="onSubmit()">
    <input v-model="titleReview" class="input is-link" type="text" placeholder="Title">

    <div class="select">
      <select v-model="rateReview">
        <option v-for="option in options" :value="option.value">
          {{ option.text }}
        </option>
      </select>
    </div>

    <textarea v-model="descriptionReview" class="textarea" placeholder="Leave a review here"></textarea>
    <footer class="modal-card-foot">
      <button class="button is-success">
        <ion-icon name="send"></ion-icon>
        Send
      </button>
    </footer>
  </form>
</template>