<script setup>
import { ref } from "vue";
import router from '@/router'
import jsCookie from 'js-cookie'
import NavBar from "../../components/NavBar.vue";

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
    "https://localhost/api/advertisements",
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
      data["hydra:member"].forEach(add => advertisements.value.push(add));
      console.log(advertisements.value);
    })
    .catch((error) => console.log(error))
</script>


<template>
  <div>
    <NavBar />
    <div class="container is-flex is-justify-content-center mb-5">
      <div class="card">
        <div class="card-content">
          <div class="content">
            <h2>Les annonces</h2>
            <hr>
            <router-link :to="{ path: '/admin/create-advertisement'}" class="button btn--lavender mt-5">
              <span>Créer une annonce</span>
            </router-link>
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
                      <div class="buttons">
                        <router-link :to="{ path: '/admin/modify-advertisement', query: { id: ad.id } }" class="button btn--lavender mt-5">
                          <span>Modifier</span>
                        </router-link>
                      </div>
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