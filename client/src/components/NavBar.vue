<script setup>
import {onMounted, ref} from "vue";
import jsCookie from "js-cookie";
import router from "@/router";
import jwtDecode from "jwt-decode";

const token = jsCookie.get("jwt");
const role = ref(false);
const status = ref(false);
const showModal = ref(false);
const error = ref(null);
const message = ref('');

onMounted(() => {
  getDataUser()
})

function getDataUser() {
  let id;
  if (token !== undefined) {
     id = jwtDecode(token).id
  }

  const requestToken = new Request(
      "https://localhost/api/users/"+id,
      {
        method: "GET",
        headers: {
          "Content-Type": "application/json",
          "Authorization": "Bearer " + token
        }
      });

  fetch(requestToken)
      .then((response) => {
        if (response.status === 200) {
          return response.json()
        }
      })
      .then((data) => {

        if (data !== undefined) {
          if (data.roles.includes('ROLE_ADMIN')) {
            role.value = 'admin'
          } else {
            role.value = 'user'
          }
          status.value = data.status
        }
      })
}

function sendRequest() {
    const id = jwtDecode(token).id

    const requestReset = new Request(
        "https://localhost/api/users/"+id,
        {
          method: "PATCH",
          body: JSON.stringify({
            status: 2
          }),
          headers: {
            "Content-Type": "application/merge-patch+json",
            "Authorization": "Bearer " + token
          }
        });
    fetch(requestReset)
        .then((response) => {
          if (response.status === 200) {
              getDataUser()
          }
        })
}

function logout() {
  jsCookie.remove("jwt");
  router.push("/login");
}
</script>

<template>
  <div>
    <nav class="navbar" role="navigation" aria-label="main navigation">
      <div class="navbar-brand">
        <router-link to="/">
          <img src="../assets/logo.png" width="90"
               height="90">
        </router-link>

        <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample">
          <span aria-hidden="true"></span>
          <span aria-hidden="true"></span>
          <span aria-hidden="true"></span>
        </a>
      </div>

      <div id="navbarBasicExample" class="navbar-menu">
        <div class="navbar-start" v-if="role === 'user'">
          <router-link to="/" class="navbar-item">
            Home
          </router-link>

          <router-link to="/bookings" class="navbar-item">
            Mes réservations
          </router-link>

          <div class="navbar-item has-dropdown is-hoverable" v-if="status === 3">
            <span class="navbar-link">
              Annonces
            </span>

            <div class="navbar-dropdown">
              <router-link to="/my-listings" class="navbar-item">
                Mes annonces
              </router-link>
              <router-link to="/refunds" class="navbar-item">
                Demandes de remboursement
              </router-link>
            </div>
          </div>
          <router-link to="/profile" class="navbar-item">
            Mon profil
          </router-link>

          <div class="is-flex is-align-items-center" v-if="status === 1">
            <button class="button is-warning is-light" @click="sendRequest">Devenir un hôte</button>
          </div>
          <div class="is-flex is-align-items-center" v-else-if="status === 2">
            <p class="has-text-info mb-0 ml-3">Votre demande d'hôte est en attente</p>
          </div>
        </div>

        <div class="navbar-start" v-else-if="role === 'admin'">
          <router-link to="/" class="navbar-item">
            Home
          </router-link>

          <router-link to="/admin/bookings" class="navbar-item">
            Réservations
          </router-link>
          <router-link to="/admin/listings-advertisements" class="navbar-item">
            Annonces
          </router-link>
          <router-link to="/admin/listings-users" class="navbar-item">
            Utilisateurs
          </router-link>
          <router-link to="/admin/host" class="navbar-item">
            Demandes d'hôtes
          </router-link>
        </div>

        <div class="navbar-end">
          <div class="navbar-item">
            <div class="buttons">
              <router-link to="/login" class="button btn--lavender" v-if="!role">
                <strong>Connexion / Inscription</strong>
              </router-link>
              <button @click="logout" class="button btn--lavender" v-else>Déconnexion</button>
            </div>
          </div>
        </div>
      </div>
    </nav>

    <div class="modal" v-bind:class="{'is-active': showModal}">
      <div class="modal-background" @click.prevent="showModal = !showModal; error = null"></div>
      <div class="modal-content">
        <div class="card">
          <div class="card-content">
            <div class="content">
              <h3 class="mb-5">Ecrivez un message pour dire pourquoi vous souhaitez devenir un hôte</h3>

              <form @submit.prevent="sendRequest()">
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
      <button class="modal-close is-large" aria-label="close" @click="showModal = !showModal; error = null"></button>
    </div>
  </div>
</template>