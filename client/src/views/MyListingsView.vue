<script setup lang="ts">
import { ref, onMounted } from "vue";
import router from '@/router'
import jsCookie from 'js-cookie'
import jwtDecode from 'jwt-decode'
import NavBar from "../components/NavBar.vue";

const user = ref(null);
const adData = ref({
  name: null,
  type: null,
  description: null,
  city: null,
  zipcode: null,
  address: null,
  dateStart: null,
  dateEnd: null,
  status: null,
  error: null
});
const advertisements = ref([]);
let token = jsCookie.get('jwt')
let idUser = jwtDecode(token).id
console.log(idUser)

const deleteAdvertisement = (id) => {
  const requestAdvertisement = new Request(
    "https://localhost/api/advertisements/"+id,
    {
      method: "PATCH",
      body: JSON.stringify({
        status: false
      }),
      headers: {
        "Content-Type": "application/merge-patch+json",
        "Authorization": "Bearer " + token
      }
    });
  fetch(requestAdvertisement)
        .then((response) => {
          if (response.status === 200) {
            callUser()
          }
        })
}
//onUnmounted
function callUser(){
  advertisements.value = []
  const requestAd = new Request(
  
    "https://localhost/api/users/"+idUser,
    {
      method: "GET",
      headers: {
        "Content-Type": "application/Id+json",
        "Authorization": "Bearer " + token
      }
    });
  fetch(requestAd)
    .then((response) => response.json())
    .then((data) => {
      console.log(data)
      data.advertisements.forEach(add => advertisements.value.push(add));
    })
    .catch((error) => console.log(error))


}
onMounted(() => {
  callUser()
})

</script>

<template>
  <div>
    <NavBar />
    <div class="container is-flex is-justify-content-center mb-5">
      <div class="card">
        <div class="card-content">
          <div class="content">
            <h2>Mes annonces</h2>
            <hr>
            <button class="button is-info" @click="router.push({name: 'create-advertisement'})">Créer une annonce</button>
            <table class="table">
              <thead>
                <tr>
                  <th>id</th>
                  <th>Titre</th>
                  <th>Date début</th>
                  <th>Date fin</th>
                  <th>Ville</th>
                  <th>Code postal</th>
                  <th>Statut</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="ad in advertisements" :key="ad.id">
                  <th>{{ad.id}}</th>
                  <td>{{ ad.name }}</td>
                  <td>{{new Date(ad.date_start).toLocaleDateString()}}</td>
                  <td>{{new Date(ad.date_end).toLocaleDateString()}}</td>
                  <td>{{ad.city}}</td>
                  <td>{{ad.zipcode}}</td>
                  <td>{{ad.status ? 'Actif' : 'Désactivé'}}</td>
                  <td>
                      <template v-if="ad.bookings.every(booking => booking.advertisement.split('/')[3] != ad.id)">
                        <div class="buttons">
                          <button class="button is-info is-light" @click="router.push({name: 'my-advertisement', query: {id: ad.id}})">Voir plus</button>
                          <button v-if="ad.status" class="button is-danger is-light" @click="deleteAdvertisement(ad.id)">Supprimer</button>
                        </div>
                      </template>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>