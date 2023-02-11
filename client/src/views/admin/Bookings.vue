<script setup>
import {onMounted, ref} from 'vue'
import jsCookie from "js-cookie";

const resultBookings = ref([])
const bookingId = ref(null)
const message = ref(null)
const error = ref(null)
const token = jsCookie.get('jwt')


onMounted(() => {
  getBookings()
})

function getBookings() {
  const request = new Request(
      "https://localhost/admin/bookings",
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
        console.log(data['hydra:member'])
        resultBookings.value = data['hydra:member']
      })
}

function sendRequest(valueRefund= false) {
  if ((message.value === '' && !valueRefund) || bookingId.value === null) {
    error.value = 'Veuillez remplir tous les champs'
  } else {
    error.value = null

    if (valueRefund) {
      message.value =''
    }

    const requestReset = new Request(
        "https://localhost/api/cancel-booking",
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
          getBookings()
        })
  }
}
</script>

<template>
  <main>
    <div class="container">
      <table class="table" v-if="resultBookings.length">
        <thead>
        <tr>
          <th>Nom</th>
          <th>Message de l'utilisateur</th>
          <th>Message de l'hôte</th>
          <th>Demande de remboursement</th>
        </tr>
        </thead>
        <tbody>
          <tr v-for="item in resultBookings" :key="item.id">
            <td>Name</td>
            <td>{{item.cancel_user}}</td>
            <td>{{item.cancel_host}}</td>
            <td>
              <p v-if="item.status === -1" class="has-text-success">Acceptée</p>
              <p v-if="item.status === 0" class="has-text-info">Aucune</p>
              <p v-if="item.status === 1" class="has-text-warning-dark">Envoyée à l'hôte</p>
              <div v-if="item.status === 2" class="is-flex">
                <button class="button is-success is-light" @click="bookingId = item.id; sendRequest(true)">Accepter l'annulation</button>
                <button class="button is-warning is-light" @click="bookingId = item.id; sendRequest(false)">Refuser l'annulation</button>
              </div>
              <p v-if="item.status === 3" class="has-text-danger">Refusée</p>
            </td>
          </tr>
        </tbody>
      </table>
      <div v-else>
        <p class="is-size-3">Aucune réservation</p>
      </div>
    </div>
  </main>
</template>
