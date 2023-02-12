<script setup>
import {onMounted, ref} from "vue";
import jsCookie from "js-cookie";
import router from "@/router";

const token = jsCookie.get("jwt");
const role = ref(false);

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
      </div>

      <div class="navbar-start" v-else>
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
</template>