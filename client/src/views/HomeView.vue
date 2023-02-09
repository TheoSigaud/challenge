<script setup>
import { onMounted, ref } from "vue";

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

onMounted(async () => {
  search();
});
</script>

<template>
  <nav
    class="navbar is-fixed-top"
    role="navigation"
    aria-label="main navigation"
  >
    <div class="navbar-brand">
      <a class="" href="/">
        <img src="../assets/logo.png" width="90" height="90" />
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
        <!-- <div class="level-item">
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
                placeholder="dd/mm/yyyy"
              ></Datepicker>
            </p>
            <p class="control">
              <Datepicker
                v-model="endDate"
                :enable-time-picker="false"
                placeholder="dd/mm/yyyy"
              ></Datepicker>
            </p>
            <p class="control">
              <button class="button" @click="search">Rechercher</button>
            </p>
          </div>
        </div> -->
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
  <!-- <div class="card">
    <div class="card-content">
      <div class="field">
        <p class="control">
          <input v-model="city" class="input" type="text" placeholder="Ville" />
        </p>
      </div>
      <div class="field">
        <p class="control">
          <Datepicker
            v-model="startDate"
            :enable-time-picker="false"
            placeholder="dd/mm/yyyy"
          ></Datepicker>
        </p>
      </div>
      <div class="field">
        <p class="control">
          <Datepicker
            v-model="endDate"
            :enable-time-picker="false"
            placeholder="dd/mm/yyyy"
          ></Datepicker>
        </p>
      </div>
      <div class="field">
        <p class="control">
          <button class="button" @click="search">Rechercher</button>
        </p>
      </div>
    </div>
  </div> -->

  <div class="level-item" style="margin-top: 110px">
    <div class="field has-addons">
      <p class="control">
        <input
          v-model="city"
          class="input input-city"
          type="text"
          placeholder="Ville"
        />
      </p>
      <p class="control">
        <Datepicker
          class="input"
          v-model="startDate"
          :enable-time-picker="false"
          placeholder="dd/mm/yyyy"
        ></Datepicker>
      </p>
      <p class="control">
        <Datepicker
          class="input"
          v-model="endDate"
          :enable-time-picker="false"
          placeholder="dd/mm/yyyy"
        ></Datepicker>
      </p>
      <p class="control" style="background-color: #00D1B2;">
        <button class="button input" style="background-color: #00D1B2;"  @click="search">Rechercher</button>
      </p>
    </div>
  </div>

  <div class="columns" style="margin-top: 10px">
    <div class="column">
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
                  <!-- <div class="media-content">
                    <p class="title is-4">{{ item.name }}</p>
                    <p class="subtitle is-6">{{ item.owner.email }}</p>
                  </div> -->
                </div>
                <div class="content">
                  <p class="title is-6">{{ item.name }}</p>
                  <br />
                  <p class="subtitle is-6">
                    Posté par : {{ item.owner.lastname.toUpperCase() }}
                    {{ item.owner.firstname }}
                  </p>
                  <p class="subtitle is-6">Contact : {{ item.owner.email }}</p>
                </div>
              </div>
            </div></router-link
          >
        </div>
      </div>
    </div>
  </div>
</template>

<style>
.input {
  height: 50px;
  border-radius: 10px 100px / 120px;
}

.control {
  border-radius: 10px 100px / 120px;
}

.card :hover {
  background-color: #00D1B2;
}
</style>
