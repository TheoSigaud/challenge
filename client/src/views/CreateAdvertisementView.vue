<script setup>
import AdvertisementForm from "../components/AdvertisementForm.vue";
import NavBar from "../components/NavBar.vue";
</script>

<template>

  <div>
    <NavBar />
    <div class="container is-flex is-justify-content-center mb-5">
      <div class="card">
        <div class="card-content">
          <div class="content">
            <AdvertisementForm method="POST"/>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>