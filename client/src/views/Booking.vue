<script setup>
import {onMounted, ref} from 'vue'
import jsCookie from "js-cookie";
import NavBar from "@/components/NavBar.vue";

const showModal = ref(false)
const resultBooking = ref([])
const bookingId = ref(null)
const message = ref(null)
const error = ref(null)
const token = jsCookie.get('jwt')


onMounted(() => {
  getBookings()
})

function getBookings() {
  const request = new Request(
      "https://localhost/api/get-bookings",
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
        resultBooking.value = data.data
      })
}

function sendRequest() {
  if (message.value === null || bookingId.value === null) {
    error.value = 'Veuillez remplir tous les champs'
  } else {
    error.value = null

    const requestReset = new Request(
        "https://localhost/api/cancel-booking",
        {
          method: "POST",
          body: JSON.stringify({
            bookingId: bookingId.value,
            message: message.value
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
          getBookings()
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
        <div v-if="resultBooking.length" v-for="item in resultBooking" :key="item.booking.id" class="mb-5"
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
                    {{ item.advertisement.description }}
                  </div>
                </div>
              </div>

              <div>
                <p>Date d'arrivée : {{new Date(item.booking.date_start).toLocaleDateString('fr-FR', {
                  day: 'numeric',
                  month: 'long',
                  year: 'numeric'
                })}}</p>
                <p>Date de départ :  {{new Date(item.booking.date_end).toLocaleDateString('fr-FR', {
                  day: 'numeric',
                  month: 'long',
                  year: 'numeric'
                })}}</p>
              </div>

              <div class="is-flex is-justify-content-space-between is-align-items-center">
                <router-link :to="{path: '/advertisement/'+item.advertisement.id}" class="button is-link is-light">Voir l'annonce</router-link>
                <div v-if="item.booking.status === 0">
                  <button class="button is-warning is-light" @click="showModal = !showModal; bookingId = item.booking.id">
                    Faire une demande d'annulation
                  </button>
                </div>

                <span v-else-if="item.booking.status === 1" class="has-text-info">La demande d'annulation a été envoyée à l'hôte</span>
                <span v-else-if="item.booking.status === 2" class="has-text-info">La demande d'annulation a envoyée à l'administrateur pour vérification</span>
                <span v-else-if="item.booking.status === -1"
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
                  <h3 class="mb-5">La demande sera envoyée directement à l'hôte</h3>

                  <form @submit.prevent="sendRequest">
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
          <button class="modal-close is-large" aria-label="close"></button>
        </div>
      </div>
    </div>
  </div>
</template>
