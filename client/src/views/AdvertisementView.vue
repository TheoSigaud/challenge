<template>
  <div>
    <NavBar />
    <div class="container custom mb-5">
      <div v-if="loading">Loading...</div>
      <div v-else>
        <div class="columns">
          <div class="column is-three-fifths">
            <div class="row" id="pictures">
              <figure class="image">
                <img src="https://bulma.io/images/placeholders/640x360.png" />
              </figure>
            </div>
            <div class="card">
              <div class="card-content pb-8">
                <h1 class="title">Description</h1>
                <p>{{ state.advertisement.description }}</p>
                <h1 class="title">Contact</h1>
                <p>{{ state.advertisement.owner?.email }}</p>
              </div>
            </div>
          </div>
          <div class="column book-card">
            <div class="card" style="display: block">
              <div class="card-content pb-8">
                <div class="columns">
                  <div class="column is-8">
                    <h1 class="title">{{ state.advertisement.name }}</h1>
                    <p>{{ state.advertisement.location }}</p>
                    <h1 class="title is-4">Ce que propose ce logement</h1>
                    <p>{{ state.advertisement?.properties }}</p>
                  </div>
                </div>
                <div class="columns">
                  <div class="column is-6">
                    <p>
                      <strong>Type de logement : </strong>
                      {{ state.advertisement.type }}
                    </p>
                  </div>
                  <div class="column is-6">
                    <p>
                      <strong>Prix par nuit: </strong>
                      {{ state.advertisement.price }} €
                    </p>
                  </div>
                </div>
                <div class="level-item">
                  <div class="field has-addons">
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
                  </div>
                </div>
                <div id="payment" class="button is-danger">Let's book !</div>
              </div>
              <div class="columns">
                <div class="column is-6">
                  <p>
                    <strong>Type de logement : </strong>
                    {{ state.advertisement.type }}
                  </p>
                </div>
                <div class="column is-6">
                  <p>
                    <strong>Prix par nuit: </strong>
                    {{ state.advertisement.price }} €
                  </p>
                </div>
              </div>
              <div class="level-item">
                <div class="field has-addons">
                  <p class="control">
                    <Datepicker
                      class="input"
                      v-model="state.startDate"
                      :enable-time-picker="false"
                      :min-date="new Date()"
                      :disabled-dates="Array.from(state.disabled)"
                      placeholder="dd/mm/yyyy"
                    ></Datepicker>
                  </p>
                  <p class="control">
                    <Datepicker
                      class="input"
                      v-model="state.endDate"
                      :max-date="getMaxDate()"
                      :enable-time-picker="false"
                      placeholder="dd/mm/yyyy"
                    ></Datepicker>
                  </p>
                </div>
              </div>
              <div id="payment" class="button is-danger">Let's book !</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, reactive } from "vue";
import { useRoute } from "vue-router";
import jsCookie from "js-cookie";
import NavBar from "@/components/NavBar.vue";

const route = useRoute();
const state = reactive({
  advertisement: {},
  loading: true,
  disabled: [],
  startDate: new Date(),
  endDate: null,
});

async function getAdvertisement() {
  console.log("mounted");
  const token = jsCookie.get("jwt");
  const id = route.params.id;
  const response = await fetch(`https://localhost/api/advertisements/${id}`, {
    headers: {
      Authorization: `Bearer ${token}`,
    },
  });
  const data = await response.json();
  console.log(data);
  state.advertisement = data;
  state.loading = false;
}

async function getDisabledDates() {
  const bookings = state.advertisement.bookings;
  const disabled = [];
  bookings.forEach((booking) => {
    const start = new Date(booking.date_start);
    const end = new Date(booking.date_end);
    while (start <= end) {
      console.log("start", start, end);
      disabled.push(new Date(start));
      start.setDate(start.getDate() + 1);
    }
  });
  return disabled;
}

async function getMaxDate() {
  console.log("state.startDate 222", state.startDate);
  const disabled = state.disabled;
  let today = new Date();

  while (today <= disabled[0]) {
    today.setDate(today.getDate() + 1);
  }

  console.log("today", today);

  return today;
}
onMounted(async () => {
  await getAdvertisement();
  state.disabled = await getDisabledDates();

  console.log("state.disabled", state.advertisement);
});
</script>

<style>
.book-card {
  position: fixed;
  top: 100px;
  right: 0;
  width: 40%;
}
.card :hover {
  background-color: white;
}
.custom {
  position: relative;
  top: 45px;
}

#pictures {
  display: inline-flex;
  margin: auto;
  width: 100%;
  align-items: center;
  justify-content: center;
}

#payment {
  display: flex;
  justify-content: center;
  width: 100;
}

#pictures figure {
  margin: 15px;
}

p {
  margin-bottom: 20px;
}
</style>
