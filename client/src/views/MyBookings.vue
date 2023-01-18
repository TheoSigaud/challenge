<script setup>
import {ref} from 'vue'
import {
  HydraAdmin,
  FieldGuesser,
  ListGuesser,
  ResourceGuesser
} from "@api-platform/admin";

const reviews = ref([]);

const requestReviews = new Request(
    "https://localhost/bookings",
    {
      method: "GET",
      headers: {
        "Content-Type": "application/json"
      }
    });

fetch(requestReviews)
    .then((response) => response.json())
    .then(data => reviews.value = data['hydra:member'])

console.log(reviews);

</script>

<template>
  <main>
    <h1> Mes réservations</h1>

    <table class="table">
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
      <tfoot>
      <tr>
        <th><abbr title="Position">ID</abbr></th>
        <th>Offre n°</th>
        <th>Booked at</th>
        <th>Booked by</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
      </tfoot>
      <tbody v-for="review in reviews">
      <tr v-if="review.client['@id'] === '/users/3'">
        <td>{{ review.id }}</td>
        <td>{{ review.advertisement['@id'] }} </td>
        <td>{{ review.date }}</td>
        <td>{{ review.client.firstname + " " + review.client.lastname }}</td>
        <td>{{ review.status }}</td>
        <td class="buttons">
          <button class="button is-primary is-light">Add a commentary</button>
          <button class="button is-primary is-light">Edit</button>
        </td>
      </tr>
      </tbody>
    </table>
  </main>
</template>
