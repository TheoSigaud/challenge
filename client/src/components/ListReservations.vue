<script>
import {ref, onMounted, watch} from 'vue';

export default {
  data() {
    return {
      selectedOption: '',
      options: [
        { value: null, text: 'Rating' },
        { value: '1', text: '1' },
        { value: '2', text: '2' },
        { value: '3', text: '3' },
        { value: '4', text: '4' },
        { value: '5', text: '5' }
      ]
    }
  },

  methods: {
    getSelectedValue() {
      console.log(this.selectedOption)
    }
  },

  setup() {
    const loading = ref(false);
    const error = ref(null);
    const reservations = ref([]);
    const reload = ref(false);
    const showModal = ref(false);
    const ad_id = ref("");

    const requestReservations = new Request(
        "https://localhost/bookings",
        {
          method: "GET",
          headers: {
            "Content-Type": "application/json"
          }
        });

    const reloadData = () => {
      if (reload.value === false) {
        reload.value = true
      }
    }

    const fetchData = async () => {
      loading.value = true
      try {
        const response = await fetch(requestReservations)
        const data = await response.json()
        reservations.value = data["hydra:member"]
      } catch (err) {
        error.value = err
      } finally {
        loading.value = false
      }
    }

    onMounted(() => {
      fetchData()
    })

    watch(() => reload.value, async () => {
      await fetchData();
      reload.value = false;
    })

    return {
      reservations,
      loading,
      error,
      reloadData,
      showModal,
      ad_id
    }
  }
}
</script>

<template>
  <button @click="reloadData" class="button is-info">
    <ion-icon name="refresh-outline"></ion-icon>
  </button>
  <div v-if="loading">
    <progress class="progress is-large is-info" max="100">60%</progress>
  </div>
  <div v-else-if="error">Error: {{ error }}</div>
  <table v-else class="table">
    <thead>
    <tr>
      <th><abbr title="Position">ID</abbr></th>
      <th>Offer n°</th>
      <th>Name of the location</th>
      <th>Description of the location</th>
      <th>Booked at</th>
      <th>Booked by</th>
      <th>Status</th>
      <th>Actions</th>
    </tr>
    </thead>
    <tbody v-for="reservation in reservations">
    <tr v-if="reservation.client['@id'] === '/users/3'">
      <td>{{ reservation.id }}</td>
      <td>{{ reservation.advertisement['@id'] }}</td>
      <td>{{ reservation.advertisement.name }}</td>
      <td>{{ reservation.advertisement.description }}</td>
      <td>{{ reservation.date }}</td>
      <td>{{ reservation.client.firstname + " " + reservation.client.lastname }}</td>
      <td>{{ reservation.status }}</td>
      <td class="buttons">
        <button class="button is-primary is-light" @click="showModal = true; ad_id = reservation.advertisement.name">
          <ion-icon name="add-outline"></ion-icon>
          Add a review
        </button>
        <button class="button is-info is-light">
          <ion-icon name="create-outline"></ion-icon>
          Edit
        </button>
      </td>
    </tr>
    </tbody>
  </table>

  <div class="modal" style="display: block;" v-if="showModal">
    <div class="modal-background"></div>
    <div class="modal-card">

      <header class="modal-card-head">
        <p class="modal-card-title">Review for {{ ad_id }}</p>
        <button class="delete" aria-label="close" v-on:click="showModal = false"></button>
      </header>

      <section class="modal-card-body">
        <input class="input is-link" type="text" placeholder="Title">

        <div class="select">
          <select v-model="selectedOption" @change="getSelectedValue">
            <option v-for="option in options" :value="option.value">
              {{ option.text }}
            </option>
          </select>
        </div>

        <textarea class="textarea" placeholder="Leave a review here"></textarea>
      </section>

      <footer class="modal-card-foot">
        <button class="button is-success">
          <ion-icon name="send"></ion-icon>
          Send
        </button>
        <button class="button" v-on:click="showModal = false">Cancel</button>
      </footer>
    </div>
  </div>

</template>