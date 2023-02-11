<script setup>
import {onMounted, ref} from 'vue'
import {useRoute, useRouter} from 'vue-router'
import jsCookie from "js-cookie";

const route = useRoute()
const router = useRouter()

const success = ref(null)
const error = ref(null)
const resultBooking = ref([])


onMounted(() => {
  const token = jsCookie.get('jwt')

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
})
</script>

<template>
  <main>
    <div class="container">
      <div v-for="item in resultBooking" :key="item.booking.id" class="mb-5" style="width: 700px">
        <div class="card is-flex is-flex-direction-row">
          <div class="card-content">
            <div class="media">
              <div class="media-left">
                <figure class="image is-128x128">
                  <img src="https://bulma.io/images/placeholders/96x96.png" alt="Placeholder image">
                </figure>
              </div>
              <div class="media-content">
                <p class="title is-4">{{item.advertisement.name}}}</p>
              </div>
            </div>

            <div class="content">
              {{item.advertisement.description}}
            </div>

            <div class="is-flex is-justify-content-space-between">
              <button class="button is-link is-light">Voir l'annonce</button>
              <button class="button is-warning is-light">Annuler la réservation</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
</template>
