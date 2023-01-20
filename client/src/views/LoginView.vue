<script setup>
  import { ref } from 'vue'
  import Register from "@/components/Register.vue";
  import Login from "@/components/Login.vue";

  const isRegister = ref(false)
</script>

<template>
  <main>
    <div class="container">
      <div class="card">
        <div class="card-content pb-1">
          <div class="content">
            <h2>Bienvenue</h2>

            <Register v-if="isRegister" />
            <Login v-else />



            <div v-if="!isRegister" class="is-flex is-justify-content-space-between">
              <p class="is-size-6 has-text-centered mt-5 is-underlined is-clickable mr-6" @click="isRegister = !isRegister">Pas de compte</p>
              <p class="is-size-6 has-text-centered mt-5 is-underlined is-clickable ml-6" @click="isRegister = !isRegister">Mot de passe oublié</p>
            </div>
            <p v-else class="is-size-6 has-text-centered	mt-5 is-underlined is-clickable" @click="isRegister = !isRegister">Se connecter</p>
          </div>
        </div>
      </div>
    </div>
  </main>
</template>
