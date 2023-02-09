<template>
  <div class="container">
    <div v-if="loading">Loading...</div>
    <div v-else>
      <!-- row of pictures -->
      <div class="row" id="pictures">
        <figure class="image">
          <img :src="photo" />
        </figure>
        <figure class="image">
          <img src="https://bulma.io/images/placeholders/600x480.png" />
        </figure>
      </div>
      <div class="row">
        <div class="columns">
          <div class="column is-half">
            <div class="card">
              <div class="card-content pb-8">
                <div class="columns">
                  <div class="column is-8">
                    <h1 class="title">{{ state.advertisement.name }}</h1>
                    <p>{{ state.advertisement.location }}</p>
                    <p>{{ state.advertisement.description }}</p>
                  </div>
                  <!-- <div class="column is-4">
          <img :src="advertisement.image" />
        </div> -->
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
                    </p>
                  </div>
                </div>
                <div id="payment" class="button is-danger">Let's book !</div>
              </div>
            </div>
          </div>
          <div class="column">
            <div class="card">
              <div class="card-content pb-8">
                <h1 class="title">Properties</h1>
                <p>{{ state.advertisement?.properties }}</p>
                <h1 class="title">Contact</h1>
                <p>{{ state.advertisement.owner?.email }}</p>
              </div>
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

const route = useRoute();
const state = reactive({
  advertisement: {},
  loading: true,
});

onMounted(async () => {
  console.log("mounted");
  const id = route.params.id;
  console.log(id);
  const response = await fetch(`https://localhost/api/advertisements/${id}`);
  const data = await response.json();
  state.advertisement = data;
  state.loading = false;
  console.log("data", data);
});
</script>

<style>
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
  margin: 30px;
}
</style>
