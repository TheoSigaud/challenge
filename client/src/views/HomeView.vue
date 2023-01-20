<script setup>
import { ref } from "vue";

const research = ref({
  city: null,
  startDate: null,
  endDate: null,
});

var data = ref({});

function search() {
  const url = new URL("https://localhost/advertisements");
  if (research.value.city !== null) {
    url.searchParams.set("city", research.value.city);
  }
  fetch(url)
    .then((response) => response.json())
    .then((_data) => {
      console.log(_data);
      if (_data["hydra:member"]) {
        data = _data["hydra:member"];
      }
      console.log(data);
    })
    .catch((error) => console.error("Error fetching advertisements:", error));
}

function getOwnerMail(owner) {
  const url = new URL("https://localhost");
  url.pathname = owner;
  console.log(url.href);
  fetch(url.href)
    .then((response) => response.json())
    .then((_data) => {console.log(_data); return _data.email})
    .catch((error) => console.error("Error fetching owner:", error));
}
</script>

<template>
  <nav
    class="navbar is-fixed-top"
    role="navigation"
    aria-label="main navigation"
  >
    <div class="navbar-brand">
      <a class="navbar-item" href="https://bulma.io">
        <img
          src="https://bulma.io/images/bulma-logo.png"
          width="112"
          height="28"
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
                v-model="research.city"
                class="input"
                type="text"
                placeholder="Find a post"
              />
            </p>
            <p class="control">
              <Datepicker
                v-model="research.startDate"
                :enable-time-picker="false"
              ></Datepicker>
            </p>
            <p class="control">
              <Datepicker
                v-model="research.endDate"
                :enable-time-picker="false"
              ></Datepicker>
            </p>
            <p class="control">
              <button class="button" @click="search">Search</button>
            </p>
          </div>
        </div>
      </div>

      <div class="navbar-end">
        <div class="navbar-item">
          <div class="buttons">
            <a class="button is-primary">
              <strong>Sign up</strong>
            </a>
            <a class="button is-light"> Log in </a>
          </div>
        </div>
      </div>
    </div>
  </nav>

  <div class="columns is-multiline">
    <div class="column is-two-fifths" v-for="item in data" :key="item.id">
      <div class="card">
        <div class="card-content">
          <div class="media">
      <div class="media-left">
        <figure class="image is-48x48">
          <img src="https://bulma.io/images/placeholders/96x96.png" alt="Placeholder image">
        </figure>
      </div>
      <div class="media-content">
        <p class="title is-4">{{ item.name }}</p>
        <!-- <p class="subtitle is-6">@johnsmith</p> -->
        <p class="subtitle is-6">{{ getOwnerMail(item.owner) }}</p>

      </div>
    </div>
          <div class="content">
            {{ item.description }}
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
