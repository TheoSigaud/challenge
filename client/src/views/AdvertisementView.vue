<template>
  <div>
    <NavBar/>
    <button class="button btn--lavender" @click="displayCheckout = !displayCheckout" v-if="displayCheckout">Retour</button>
    <div class="container custom mb-5" v-if="!displayCheckout">
      <div>
        <div class="columns">
          <div class="column is-three-fifths">
            <div class="card">
              <div class="card-image">
                <figure class="image is-4by3">
                  <img src="https://bulma.io/images/placeholders/1280x960.png" alt="Placeholder image">
                </figure>
              </div>
              <div class="card-content">
                <div class="media">
                  <div class="media-content">
                    <p class="title is-3">{{ state.advertisement.name }}</p>
                  </div>
                </div>

                <div class="content">
                  <p class="title is-5">Ce que propose ce logement</p>
                  <br>
                  <time datetime="2016-1-1">11:09 PM - 1 Jan 2016</time>
                </div>
              </div>
            </div>
          </div>

          <div class="column">
            <div class="card">
              <div class="card-content">
                <div class="content">
                  <div class="mb-5">
                    <p class="title is-4">{{ state.advertisement.price }} € <span class="subtitle is-6">par nuit</span>
                    </p>
                  </div>

                  <div class="field has-addons">
                    <Datepicker
                        v-model="state.startDate"
                        :enable-time-picker="false"
                        :min-date="new Date()"
                        :disabled-dates="Array.from(state.disabled)"
                        placeholder="dd/mm/yyyy"
                    ></Datepicker>
                    <Datepicker
                        v-model="state.endDate"
                        :min-date="new Date(state.startDate) + 1"
                        :max-date="state.maxDate"
                        :enable-time-picker="false"
                        placeholder="dd/mm/yyyy"
                    ></Datepicker>
                  </div>

                  <div class="mt-3 is-flex is-jutify-content-center">
                    <button class="button is-danger" @click="checkValue">Je réserve !</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <Checkout
        :price="state.advertisement.price"
        :dateStart="state.startDate"
        :dateEnd="state.endDate"
        :id="state.id"
        v-else/>
  </div>
</template>

<script setup>
import {onMounted, reactive, ref, watch} from "vue";
import {useRoute} from "vue-router";
import jsCookie from "js-cookie";
import NavBar from "@/components/NavBar.vue";
import Checkout from "@/components/Checkout.vue";

const route = useRoute();
const displayCheckout = ref(false);
const state = reactive({
  advertisement: {},
  id: route.params.id,
  loading: true,
  disabled: [],
  startDate: new Date(),
  endDate: null,
  maxDate: null,
});

function checkValue() {
  if (state.startDate && state.endDate && state.advertisement.price && state.id) {
    displayCheckout.value = true;
  } else {

  }
}

async function getAdvertisement() {
  const token = jsCookie.get("jwt");
  const id = route.params.id;
  const response = await fetch(`https://localhost/advertisements/${id}`, {
    headers: {
      Authorization: `Bearer ${token}`,
    },
  });
  const data = await response.json();
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
/*.book-card {*/
/*  position: fixed;*/
/*  top: 100px;*/
/*  right: 0;*/
/*  width: 40%;*/
/*}*/
/*.card :hover {*/
/*  background-color: white;*/
/*}*/
/*.custom {*/
/*  top: -100px;*/
/*}*/

/*#pictures {*/
/*  display: inline-flex;*/
/*  margin: auto;*/
/*  width: 100%;*/
/*  align-items: center;*/
/*  justify-content: center;*/
/*}*/

/*#payment {*/
/*  display: flex;*/
/*  justify-content: center;*/
/*  width: 100;*/
/*}*/

/*#pictures figure {*/
/*  margin: 15px;*/
/*}*/

/*p {*/
/*  margin-bottom: 20px;*/
/*}*/
</style>
