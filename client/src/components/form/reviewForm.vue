<script>
import {ref} from "vue";
import jsCookie from 'js-cookie';

export default {
  props: ['ad_id'],
  data() {
    return {
      selectedOption: '',
      options: [
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
    const rateReview = ref("Note")
    const statusFetch = ref(null)

    const onSubmit = async () => {
      try {
        const token = jsCookie.get('jwt')
        console.log(token)
        const response = await fetch("https://localhost/api/comments", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "Authorization": "Bearer " + token
          },
          body: JSON.stringify({
            advertisement: "/advertisement/" + props.ad_id,
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
        statusFetch.value = ["Merci pour votre avis", "Avis envoyé, il sera traité par l'administrateur avant sa publication", "success"]
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
    <input v-model="titleReview" class="input is-link" type="text" placeholder="Titre">

    <div class="select">
      <select v-model="rateReview">
        <option>Note</option>
        <option v-for="option in options" :value="option.value">
          {{ option.text }}
        </option>
      </select>
    </div>

    <textarea v-model="descriptionReview" class="textarea" placeholder="Ajouter un commentaire"></textarea>
    <footer class="modal-card-foot">
      <button class="button is-success">
        <ion-icon name="send"></ion-icon>
        Envoyer
      </button>
    </footer>
  </form>
</template>

