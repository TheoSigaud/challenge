<script setup>
import { ref } from "vue";
import router from '@/router'
import jsCookie from 'js-cookie'
import NavBar from '@/components/NavBar.vue'

let token = jsCookie.get('jwt')
const users = ref([]);

const unsetAdmin =  (idUser) => {
  const requestRegister = new Request(
        "https://localhost/api/users/"+idUser,
        {
          method: "PATCH",
          body: JSON.stringify({
            roles: ["ROLE_USER"]
          }),
          headers: {
            "Content-Type": "application/merge-patch+json",
            "Authorization": "Bearer " + token
          }
        });
      fetch(requestRegister)
            .then((response) => console.log('ok admin'))
}

const changeAdmin =  (idUser) => {
  const requestRegister = new Request(
        "https://localhost/api/users/"+idUser,
        {
          method: "PATCH",
          body: JSON.stringify({
            roles: ["ROLE_USER","ROLE_ADMIN"]
          }),
          headers: {
            "Content-Type": "application/merge-patch+json",
            "Authorization": "Bearer " + token
          }
        });
      fetch(requestRegister)
            .then((response) => console.log('ko admin'))
}

const requestUser = new Request(
    "https://localhost/admin/users",
    {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
        "Authorization": "Bearer " + token
      }
    });
  fetch(requestUser)
    .then((response) => response.json())
    .then((data) => {
      data["hydra:member"].forEach(add => users.value.push(add));
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
            <h2>Les utilisateurs</h2>
            <hr>
            <table class="table">
              <thead>
                <tr>
                  <th><abbr title="Id">id</abbr></th>
                  <th><abbr title="Prénom">Prénom</abbr></th>
                  <th><abbr title="Nom de famille">Nom de famille</abbr></th>
                  <th><abbr title="Email">Email</abbr></th>
                  <th><abbr title="Date de naissance">Date de naissance</abbr></th>
                  <th><abbr title="Adresse">Adresse</abbr></th>
                  <th><abbr title="Administrateur">Administrateur</abbr></th>
                  <th><abbr title="Action">Action</abbr></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="user in users" :key="user.id">
                  <th>{{user.id}}</th>
                  <td>{{ user.firstname }}</td>
                  <td>{{user.lastname}}</td>
                  <td>{{user.email}}</td>
                  <td>{{new Date(user.birthday).toLocaleDateString()}}</td>
                  <td>{{user.address}}</td>
                  <template v-if="user.roles.includes('ROLE_ADMIN')">
                    <td>Oui</td>
                  </template>
                  <template v-else>
                    <td>Non</td>
                  </template>
                  <td>
                      <div class="buttons">
                        <router-link :to="{ path: '/admin/update-user', query: { id: user.id } }" class="button btn--lavender mt-5">
                          <span>Voir plus</span>
                        </router-link>
                        <button v-if="!user.roles.includes('ROLE_ADMIN')" class="button is-info" @click="changeAdmin(user.id)">Passer administrateur</button>
                        <button v-if="user.roles.includes('ROLE_ADMIN')" class="button is-info" @click="unsetAdmin(user.id)">Supprimer administrateur</button>
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