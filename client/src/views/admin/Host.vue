<script setup>
import {onMounted, ref} from 'vue'
import jsCookie from "js-cookie";
import NavBar from "@/components/NavBar.vue";

const users = ref([])
const message = ref(null)
const error = ref(null)
const token = jsCookie.get('jwt')


onMounted(() => {
  getBookings()
})

function getBookings() {
  const request = new Request(
      "https://localhost/admin/users-host?status=2",
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
        console.log(data)
        users.value = data['hydra:member']
      })
}
</script>

<template>
  <div>
    <NavBar />
    <div class="container is-flex is-justify-content-center mb-5">
      <table class="table" v-if="users.length">
        <thead>
        <tr>
          <th>Nom</th>
          <th>Prénom</th>
          <th>Date de naissance</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="item in users" :key="item.id">
          <td>{{item.lastname}}</td>
          <td>{{ item.firstname }}</td>
          <td>{{ new Date(item.birthday).toLocaleDateString('fr-FR', {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
          }) }}</td>
        </tr>
        </tbody>
      </table>
      <div v-else>
        <p class="is-size-3">Aucune demande d'hôte</p>
      </div>
    </div>
  </div>
</template>
