<script>
import {ref} from "vue";
import jsCookie from 'js-cookie';
import jwtDecode from 'jwt-decode';

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

  setup(props) {
    const titleReview = ref("")
    const descriptionReview = ref("")
    const rateReview = ref("")
    const statusFetch = ref(null)

    const onSubmit = async () => {
      try {
        const token = jsCookie.get('jwt')
        console.log(token)
        const idUser = jwtDecode(token)
        console.log(idUser)
        const response = await fetch("https://localhost/comments", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "Authorization": "Bearer " + token
          },
          body: JSON.stringify({
            advertisement: props.ad_id,
            client: props.c_id,
            title: titleReview.value,
            message: descriptionReview.value,
            rate: parseFloat(rateReview.value),
            status: 0
          })
        });
        if (!response.ok) {
          const data = await response.json()
          statusFetch.value = ["Error", data["hydra:description"], "danger"]
          throw new Error("An error occurred while submitting the form");
        }
        statusFetch.value = ["Sent", "Review sent", "success"]
      } catch (error) {
          console.log(error)
      }
    }

    return {
      titleReview,
      descriptionReview,
      rateReview,
      onSubmit,
      statusFetch
    }
  }

}
</script>
<template>
  <Notification v-if="statusFetch"
                :title="statusFetch[0]"
                :message="statusFetch[1]"
                :type="statusFetch[2]"
                @hideNotification = "statusFetch = null"
  />
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

