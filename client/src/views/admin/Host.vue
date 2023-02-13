<script setup>
import {onMounted, ref} from 'vue'
import jsCookie from "js-cookie";
import NavBar from "@/components/NavBar.vue";
import jwtDecode from "jwt-decode";

const users = ref([])
const message = ref(null)
const error = ref(null)
const token = jsCookie.get('jwt')


onMounted(() => {
  getHost()
})

function getHost() {
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

function sendRequest(value = false, id) {

  if (value === true) {
    const requestReset = new Request(
        "https://localhost/api/users/" + id,
        {
          method: "PATCH",
          body: JSON.stringify({
            role: ["ROLE_HOST", "ROLE_USER"]
          }),
          headers: {
            "Content-Type": "application/merge-patch+json",
            "Authorization": "Bearer " + token
          }
        });
    fetch(requestReset)
        .then((response) => {
          if (response.status === 200) {
            getHost()
          }
        })
  }
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
          <th>Message</th>
          <th>Actions</th>
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
          <td>
            {{ item.message_host}}
          </td>
          <td>
            <button class="button is-success is-light mr-3" @click="sendRequest(true, item.id)">Accepter</button>
            <button class="button is-danger is-light" @click="sendRequest(false, item.id)">Refuser</button>
          </td>
        </tr>
        </tbody>
      </table>
      <div v-else>
        <p class="is-size-3">Aucune demande d'hôte</p>
      </div>
    </div>
  </div>
</template>
