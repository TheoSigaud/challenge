<script setup>
import { ref } from "vue";

const city = ref("");
const startDate = ref("");
const endDate = ref("");
const init = ref(true);
const data = ref({});

function search() {
  const url = new URL("https://localhost/api/advertisements");
  if (city.value !== "") {
    url.searchParams.set("city", city.value);
  } else if (startDate._value !== "") {
    let date = new Date(startDate._value);
    url.searchParams.set("date_start", date.toISOString().slice(0, 10));
  } else if (endDate._value !== "") {
    let date = new Date(endDate._value);
    url.searchParams.set("endDate", date.toISOString().slice(0, 10));
  }
  console.log(url.href);
  fetch(url.href)
    .then((response) => response.json())
    .then((_data) => {
      console.log(_data);
      if (_data["hydra:member"]) {
        data.value = _data["hydra:member"];
      }
      console.log(data.value);
    })
    .catch((error) => console.error("Error fetching advertisements:", error));

  console.log("search", city.value, startDate._value, endDate._value);
}
</script>

<template>
  <nav
    class="navbar is-fixed-top"
    role="navigation"
    aria-label="main navigation"
  >
    <div class="navbar-brand">
      <a class="" href="/">
        <img
          src="../assets/logo.png"
          width="90"
          height="90"
        />
      </a>

      <a
        role="button"
        class="navbar-burger"
        aria-label="menu"
        aria-expanded="false"
        data-target="navbarBasicExample"
      >
        <span aria-hidden="true"></span>
        <span aria-hidden="true"></span>
        <span aria-hidden="true"></span>
      </a>
    </div>

    <div id="navbarBasicExample" class="navbar-menu">
      <div class="navbar-start" style="flex-grow: 1; justify-content: center">
        <div class="level-item">
          <div class="field has-addons">
            <p class="control">
              <input
                v-model="city"
                class="input"
                type="text"
                placeholder="Ville"
              />
            </p>
            <p class="control">
              <Datepicker
                v-model="startDate"
                :enable-time-picker="false"
              ></Datepicker>
            </p>
            <p class="control">
              <Datepicker
                v-model="endDate"
                :enable-time-picker="false"
              ></Datepicker>
            </p>
            <p class="control">
              <button class="button" @click="search">Rechercher</button>
            </p>
          </div>
        </div>
      </div>

      <div class="navbar-end">
        <div class="navbar-item">
          <div class="buttons">
            <router-link class="button is-primary" to="/login">
              <strong>Se connecter</strong>
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </nav>
  <div v-if="!data.length">
    <!-- Carte d'accueil ici -->
    <div class="card bd-is-size-1-2">
      <div class="card-content">
        <p class="title">Projet Scolaire</p>
        <p class="subtitle">
          Conçu par Samy HAMED E SABERI, SIGAUD Théo, KAJEIOU Mohamed et
          MEKHICHE Sid Ahmed
        </p>
        <p>Dans le cadre du challenge IW SEMESTRE 1</p>
      </div>
    </div>
  </div>
  <div v-else>
    <div class="columns is-multiline">
      <div class="column is-one-third" v-for="item in data" :key="item.id">
        <router-link
          class="nav-link"
          :to="{ name: 'advertisement', params: { id: item.id } }"
        >
          <div class="card">
            <div class="card-content">
              <div class="media">
                <div class="media-left">
                  <figure class="image">
                    <img
                      src="https://bulma.io/images/placeholders/96x96.png"
                      alt="Placeholder image"
                    />
                  </figure>
                </div>
                <div class="media-content">
                  <p class="title is-4">{{ item.name }}</p>
                  <!-- <p class="subtitle is-6">@johnsmith</p> -->
                  <!-- <p class="subtitle is-6">{{ getOwnerMail(item.owner) }}</p> -->
                  <p class="subtitle is-6">{{ item.owner.email }}</p>
                </div>
              </div>
              <div class="content">
                {{ item.description }}
              </div>
            </div>
          </div></router-link
        >
      </div>
    </div>
  </div>
</template>
