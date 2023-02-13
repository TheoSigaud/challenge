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
                        :min-date="new Date(state.startDate) + 1"
                        :max-date="state.maxDate"
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
  </div>
</template>

<script setup>
import { onMounted, reactive, watch } from "vue";
import { useRoute } from "vue-router";
import jsCookie from "js-cookie";
import NavBar from "@/components/NavBar.vue";

const route = useRoute();
const state = reactive({
  advertisement: {},
  id: route.params.id,
  loading: true,
  disabled: [],
  startDate: new Date(),
  endDate: null,
  maxDate: null,
});

async function getAdvertisement() {
  const token = jsCookie.get("jwt");
  const id = route.params.id;
  const response = await fetch(`https://localhost/advertisements/${id}`, {
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
      disabled.push(new Date(start));
      start.setDate(start.getDate() + 1);
    }
  });

  const startDate = new Date(state.startDate);
  disabled.forEach((date) => {
    if (date < startDate) {
      disabled.splice(disabled.indexOf(date), 1);
    }
  });
  console.log("disabled", disabled);
  return disabled;
}

async function getLastAvailableDate() {
  const disabledDates = state.disabled;
  let lastAvailableDate = null;

  for (let i = 0; i < disabledDates.length; i++) {
    if (disabledDates[i] > state.startDate) {
      lastAvailableDate = disabledDates[i];
      break;
    }
  }

  return lastAvailableDate;
}

onMounted(async () => {
  await getAdvertisement();
  state.disabled = await getDisabledDates().then(
    (disabledDates) => disabledDates
  );
  state.maxDate = await getLastAvailableDate().then((resolvedMaxDate) => resolvedMaxDate);
});

watch(
  () => state.startDate,
  async () => {
    state.disabled = await getDisabledDates().then(
      (disabledDates) => disabledDates
    );
    state.maxDate = await getLastAvailableDate().then(
      (resolvedMaxDate) => resolvedMaxDate
    );
  }
);
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
  top: -100px;
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
