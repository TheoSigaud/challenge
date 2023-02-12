<template>
  <div>
    <NavBar />
    <div class="container is-flex is-justify-content-center mb-5">
      <div class="is-flex is-jutify-content-center is-flex-direction-column">
        <p class="is-size-3	">Cette page n'existe pas</p>

        <router-link to="/" class="button btn--lavender mt-5">
          <span>Retour à l'accueil</span>
        </router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import NavBar from "@/components/NavBar.vue";
</script>