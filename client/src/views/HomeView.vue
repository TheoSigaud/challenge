<script setup>
import {onMounted, ref} from "vue";
import jsCookie from "js-cookie";
import NavBar from "@/components/NavBar.vue";

const city = ref("");
const startDate = ref("");
const endDate = ref("");
const init = ref(true);
const data = ref({});
const error = ref("");

function search() {
  error.value = "";
  // if (city.value === "" && startDate.value === "" && endDate.value === "") {
  //   console.log("error");
  //   error.value = "Veuillez remplir tout les champs";
  //   return;
  // }

  const requestReset = new Request(
      "https://localhost/search-advertisements/?city=" + city.value,
      {
        method: "GET",
      });
  fetch(requestReset)
      .then((response) => response.json())
      .then((_data) => {
        if (_data["hydra:member"]) {
          data.value = _data["hydra:member"];
        }
      })
}

onMounted(async () => {
  const requestReset = new Request(
      "https://localhost/advertisements",
      {
        method: "GET",
      });
  fetch(requestReset)
      .then((response) => response.json())
      .then((_data) => {
        if (_data["hydra:member"]) {
          data.value = _data["hydra:member"];
        }
      })
});
</script>

<template>
  <div>
    <NavBar :key="'home'" />
    <div class="container is-flex is-justify-content-center mb-5">
      <div class="level-item custom-class">
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
          <p class="control" style="background-color: #00d1b2">
            <button
                class="button input"
                style="background-color: #00d1b2"
                @click="search"
            >
              Rechercher
            </button>
          </p>
        </div>
      </div>
      <p v-if="error" class="text is-danger">{{error}}</p>

      <div class="custom-class2">
        <div class="columns">
          <div class="column">
            <div class="columns is-multiline">
              <div class="column is-one-third" v-for="item in data" :key="item.id">
                <router-link
                    class="nav-link"
                    :to="{ name: 'advertisement', params: { id: item.id } }"
                >
                  <div class="card advertisement">
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
                      </div>
                      <div class="content">
                        <p class="title is-6">{{ item.name }}</p>
                        <br/>
                        <p class="subtitle is-6">
                          Posté par : {{ item.owner.firstname }}
                        </p>
                        <p class="subtitle is-6">
                          Contact : {{ item.owner.email }}
                        </p>
                      </div>
                    </div>
                  </div>
                </router-link
                >
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.input {
  height: 50px;
  border-radius: 10px 100px / 120px;
}

.custom-class {
  /*position: absolute;*/
  right: 0;
  left: 0;
}

.custom-class2 {
  position: absolute;
  top: 200px; /* the height of the navbar */
  right: 0;
  left: 0;
  width: 80%;
  margin: auto;
}

.control {
  border-radius: 10px 100px / 120px;
}

.advertisement :hover {
  background-color: #e9f0f0;
}
</style>
