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
  position: absolute;
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
