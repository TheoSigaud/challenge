<script setup>
import {onMounted, ref} from 'vue'
import jsCookie from "js-cookie";
import NavBar from "@/components/NavBar.vue";

const showModal = ref(false)
const resultRefunds = ref([])
const bookingId = ref(null)
const message = ref('')
const error = ref(null)
const token = jsCookie.get('jwt')


onMounted(() => {
  getRefunds()
})

function getRefunds() {
  const request = new Request(
      "https://kaitokid.fr/api/get-refunds",
      {
        method: "GET",
        headers: {
          "Content-Type": "application/json",
          "Authorization": "Bearer " + token
        }
      });

  fetch(request)
      .then(response => response.json())
      .then(data => {
        resultRefunds.value = data.data
      })
}

function sendRequest(valueRefund = false) {
  if ((message.value === '' && !valueRefund) || bookingId.value === null) {
    error.value = 'Veuillez remplir tous les champs'
  } else {
    error.value = null

    if (valueRefund) {
      message.value = ''
    }

    const requestReset = new Request(
        "https://kaitokid.fr/api/cancel-booking",
        {
          method: "POST",
          body: JSON.stringify({
            bookingId: bookingId.value,
            message: message.value,
            valueRefund: valueRefund
          }),
          headers: {
            "Content-Type": "application/json",
            "Authorization": "Bearer " + token
          }
        });
    fetch(requestReset)
        .then((response) => {
          message.value = null
          bookingId.value = null
          getRefunds()
          showModal.value = !showModal;
        })
  }
}
</script>

<template>
  <div>
    <NavBar />
    <div class="container is-flex is-justify-content-center mb-5">
      <div>
        <div v-if="resultRefunds.length" v-for="item in resultRefunds" :key="item.booking.id" class="mb-5"
             style="width: 700px">
          <div class="card">
            <div class="card-content">
              <div class="media">
                <div class="media-left">
                  <figure class="image is-128x128">
                    <img src="https://bulma.io/images/placeholders/96x96.png" alt="Placeholder image">
                  </figure>
                </div>
                <div class="media-content">
                  <p class="title is-4">{{ item.advertisement.name }}</p>
                  <div class="content">
                    Raison de l'annulation : <br>{{ item.booking.cancel_user }}
                  </div>
                </div>
              </div>

              <div v-if="item.booking.status === 1" class="is-flex is-justify-content-space-between is-align-items-center">
                <button class="button is-success is-light" @click="bookingId = item.booking.id; sendRequest(true)">Accepter
                  l'annulation
                </button>
                <button class="button is-warning is-light"
                        @click="showModal = !showModal; bookingId = item.booking.id; valueRefund = false">Refuser
                  l'annulation
                </button>
              </div>
              <div v-else-if="item.booking.status === 2">
                <span
                    class="has-text-info">La demande d'annulation a été envoyée à un administrateur pour vérification</span>
              </div>
              <div v-else-if="item.booking.status === -1">
                <span
                    class="has-text-info">L'annulation a bien été prise en compte.<br>Le remboursement est en cours.</span>
              </div>
            </div>
          </div>
        </div>

        <div v-else>
          <p class="is-size-3">Aucune réservation</p>
        </div>

        <div class="modal" v-bind:class="{'is-active': showModal}">
          <div class="modal-background" @click.prevent="showModal = !showModal; error = null"></div>
          <div class="modal-content">
            <div class="card">
              <div class="card-content">
                <div class="content">
                  <h3 class="mb-5">La demande sera envoyée directement à l'administrateur</h3>

                  <form @submit.prevent="sendRequest(false)">
                    <textarea v-model="message" class="textarea" placeholder="Votre message" rows="10"></textarea>

                    <p v-if="error" class="has-text-centered has-text-danger">{{ error }}</p>
                    <div class="is-flex is-justify-content-center mt-6">
                      <button class="button btn--lavender" type="submit">Envoyer</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
          <button class="modal-close is-large" aria-label="close" @click="showModal = !showModal; error = null"></button>
        </div>
      </div>
    </div>
  </div>
</template>
