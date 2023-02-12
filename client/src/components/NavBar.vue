<script setup>
import {onMounted, ref} from "vue";
import jsCookie from "js-cookie";
import router from "@/router";

const token = jsCookie.get("jwt");
const role = ref(false);
const showModal = ref(false);
const error = ref(null);
const message = ref('');

onMounted(() => {
  const requestToken = new Request(
      "https://localhost/api/auth",
      {
        method: "POST",
        headers: {
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

        if (data.data.roles.includes('ROLE_ADMIN')) {
          role.value = 'admin'
        } else {
          role.value = 'user'
        }
      })
})

function logout() {
  jsCookie.remove("jwt");
  router.push("/");
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

          <div class="navbar-item has-dropdown is-hoverable">
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

          <div class="is-flex is-align-items-center">
            <button class="button is-warning is-light" @click="showModal = !showModal">Devenir un hôte</button>
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

              <form @submit.prevent="sendRequest(false)">
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