<script setup>
import AdvertisementAdminForm from "../../components/AdvertisementAdminForm.vue";
import NavBar from "../../components/NavBar.vue";
</script>

<template>
  <div>
    <NavBar />
    <div class="container is-flex is-justify-content-center mb-5">
      <div class="card">
        <div class="card-content">
          <div class="content">
            <AdvertisementAdminForm method="PATCH"/>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>