<script setup>
import {onMounted, ref} from "vue";
import jsCookie from "js-cookie";
import NavBar from "@/components/NavBar.vue";

const city = ref("");
const startDate = ref("");
const endDate = ref("");
const init = ref(true);
const data = ref({});

function search() {
  const url = new URL("https://localhost/advertisements");
  const token = jsCookie.get("jwt");
  if (city.value !== "") {
    url.searchParams.set("city", city.value.toLowerCase());
  } else if (startDate.value !== "") {
    let date = new Date(startDate.value);
    let date_2 = encodeURIComponent(date.toISOString().split('T')[0]); 
    console.log(date_2);
    url.searchParams.set("date_start", encodeURIComponent(date.toISOString().split('T')[0]));
  } else if (endDate.value !== "") {
    let date = new Date(endDate._value);
    url.searchParams.set("endDate", date.toISOString().slice(0, 10));
  }

  const request = {
    method: "GET",
    headers: {
      Authorization: `Bearer ${token}`,
    },
  };

  fetch(url, request)
      .then((response) => response.json())
      .then((_data) => {
        console.log(_data);
        if (_data["hydra:member"]) {
          data.value = _data["hydra:member"];
        }
        console.log(data.value);
      })
      .catch((error) => console.error("Error fetching advertisements:", error));

  console.log("search", city.value, startDate.value, endDate.value);
}

onMounted(async () => {
  search();
});
</script>

<template>
  <div>
    <NavBar :key="'home'" />
    <div class="container is-flex is-justify-content-center mb-5">
      <div>
      <div class="level-item custom-class">
        <div class="field has-addons mb-5">
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
                class="button input is-info is-light"
                @click="search"
            >
              Rechercher
            </button>
          </p>
        </div>
      </div>
      <div class="custom-class2 mt-5">
        <div class="columns">
          <div class="column">
            <div class="columns is-multiline">
              <div class="column is-one-third" v-for="item in data" :key="item.id">
                <router-link
                    class="nav-link"
                    :to="{ name: 'advertisement', params: { id: item.id } }"
                >
                  <div class="card advertisement">
                    <div class="card-image">
                      <figure class="image is-4by3">
                        <img :src="item.photo[Object.keys(item.photo)[0]]" alt="Placeholder image">
                      </figure>
                    </div>
                    <p>{{item.photo[0]}}</p>
                    <div class="card-content">
                      <div class="media">
                        <div class="media-content">
                          <p class="title is-4">{{ item.name }}</p>
                          <p class="subtitle is-6">Posté par : {{ item.owner.firstname }} dzd,{{ item.owner.lastname }}</p>
                        </div>
                      </div>

                      <div class="content">
                        <p>{{ item.description }}</p>
                        <br>
                        <span>Contact : {{ item.owner.email }}</span>
                      </div>
                    </div>
                  </div>
                </router-link>
              </div>
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
}

.control {
  border-radius: 10px 100px / 120px;
}
</style>
