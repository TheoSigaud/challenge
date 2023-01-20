<script setup lang="ts">
import { ref } from "vue";
import router from '@/router'
import jsCookie from 'js-cookie'

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
  error: null
});
const advertisements = ref([]);
let token = jsCookie.get('jwt')

const requestAd = new Request(
  
  //USER ID AMODIFIER
    "https://localhost/api/users/13",
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
      data.advertisements.forEach(add => advertisements.value.push(add));
    })
    .catch((error) => console.log(error))
</script>

<template>
  <div class="container">
    <div class="card">
      <div class="card-content">
        <div class="content">
          <h2>Mes annonces</h2>
          <hr>
          <table class="table">
            <thead>
              <tr>
                <th><abbr title="Id">id</abbr></th>
                <th><abbr title="Titre">Titre</abbr></th>
                <th><abbr title="Date de début">Date début</abbr></th>
                <th><abbr title="Date de fin">Date fin</abbr></th>
                <th><abbr title="Ville">Ville</abbr></th>
                <th><abbr title="Code postal">Code postal</abbr></th>
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
                <td>
                  <a href="#">
                    <div class="buttons">
                      <button class="button is-info" @click="router.push({name: 'my-advertisement', query: {id: ad.id}})"><a href="">Voir plus </a></button>
                    </div>                    
                  </a>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>