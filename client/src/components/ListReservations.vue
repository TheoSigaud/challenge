<script>
import {ref, onMounted, watch} from 'vue';

export default {
  setup() {
    const loading = ref(false);
    const error = ref(null);
    const reservations = ref([]);
    const reload = ref(false);

    const requestReservations = new Request(
        "https://localhost/bookings",
        {
          method: "GET",
          headers: {
            "Content-Type": "application/json"
          }
        });

    const reloadData = () => {
      console.log("ok")
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
      reloadData
    }
  }
}
</script>

<template>
  <button @click="reloadData">Recharger les données</button>
  <div v-if="loading">
    <progress class="progress is-large is-info" max="100">60%</progress>
  </div>
  <div v-else-if="error">Error: {{ error }}</div>
  <table v-else class="table">
    <thead>
    <tr>
      <th><abbr title="Position">ID</abbr></th>
      <th>Offre n°</th>
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
      <td>{{ reservation.date }}</td>
      <td>{{ reservation.client.firstname + " " + reservation.client.lastname }}</td>
      <td>{{ reservation.status }}</td>
      <td class="buttons">
        <button class="button is-primary is-light">Add a commentary</button>
        <button class="button is-primary is-light">Edit</button>
      </td>
    </tr>
    </tbody>
  </table>
</template>