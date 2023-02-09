<template>
  <div>
    <div v-if="loading">Loading...</div>
    <div v-else>
      {{ advertisement }}
    </div>
  </div>
</template>

<script setup>
import { onMounted } from "vue";
import { useRoute } from "vue-router";

const route = useRoute();

onMounted(async () => {
  console.log("mounted");
  const id = route.params.id;
  console.log(id);
  // await fetch(`https://localhost/api/advertisements/${id}`)
  // .then(response => {
  //   const data = response.json();
  //   console.log("data", data);
  //   return data;
  // })
  const response = await fetch(`https://localhost/api/advertisements/${id}`);
  const data = await response.json();
  console.log("data", data);
});
</script>
