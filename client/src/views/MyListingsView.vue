<script setup lang="ts">
import { ref } from "vue";
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
        .then((response) => router.push({name: 'my-listings'}))
}
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
                  <th><abbr title="Id">id</abbr></th>
                  <th><abbr title="Titre">Titre</abbr></th>
                  <th><abbr title="Date de début">Date début</abbr></th>
                  <th><abbr title="Date de fin">Date fin</abbr></th>
                  <th><abbr title="Ville">Ville</abbr></th>
                  <th><abbr title="Code postal">Code postal</abbr></th>
                  <th><abbr title="Statut">Statut</abbr></th>
                  <th><abbr title="Action">Action</abbr></th>
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
                    <a href="#">
                      <template v-if="ad.bookings.every(booking => booking.advertisement.split('/')[3] != ad.id)">
                        <div class="buttons">
                          <button class="button is-info" @click="router.push({name: 'my-advertisement', query: {id: ad.id}})"><a href="">Voir plus </a></button>
                          <button v-if="ad.status" class="button is-danger" @click="deleteAdvertisement(ad.id)"><a href="">Supprimer</a></button>
                        </div>
                      </template>
                      <template v-else>
                      </template>
                    </a>
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