<script setup>
  import { ref } from 'vue'
  import Register from "@/components/Register.vue";
  import Login from "@/components/Login.vue";

  const isRegister = ref(false)
  const showModal = ref(false)
  const resetEmail = ref(null)
  const resetSuccess = ref(null)

  function resetPassword() {
    const requestReset = new Request(
        "https://localhost/reset/email",
        {
          method: "POST",
          body: JSON.stringify({
            email: resetEmail.value,
          }),
          headers: {
            "Content-Type": "application/json"
          }
        });

    fetch(requestReset)
        .then((response) => {
          resetSuccess.value = 'Si votre email est valide, vous allez recevoir un email de réinitialisation de mot de passe.'
          showModal.value = false
        })
  }
</script>

<template>
  <main>
    <div class="container">
      <div class="card">
        <div class="card-content pb-1">
          <div class="content">
            <h2 class="has-text-centered">Bienvenue</h2>

            <Register v-if="isRegister" />
            <Login v-else />

            <p v-if="resetSuccess" class="has-text-centered has-text-success mt-5">{{resetSuccess}}</p>
            <div v-if="!isRegister" class="is-flex is-justify-content-space-between">
              <p class="is-size-6 has-text-centered mt-5 is-underlined is-clickable mr-6" @click="isRegister = !isRegister">Pas de compte</p>
              <p class="is-size-6 has-text-centered mt-5 is-underlined is-clickable ml-6" @click="showModal = !showModal">Mot de passe oublié</p>
            </div>
            <p v-else class="is-size-6 has-text-centered	mt-5 is-underlined is-clickable" @click="isRegister = !isRegister">Se connecter</p>
          </div>
        </div>
      </div>

      <div class="modal" v-bind:class="{'is-active': showModal}">
        <div class="modal-background" @click.prevent="showModal = !showModal"></div>
        <div class="modal-content">
          <div class="card">
            <div class="card-content">
              <div class="content">
                <h3>Réinitialisation du mot de passe</h3>

                <form @submit.prevent="resetPassword">
                  <div class="field">
                    <label class="label">Email</label>
                    <div class="control">
                      <input v-model="resetEmail" class="input" type="email" placeholder="alexsmith@gmail.com">
                    </div>
                  </div>

                  <div class="is-flex is-justify-content-center mt-6">
                    <button class="button is-primary" type="submit">Réinitialiser</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <button class="modal-close is-large" aria-label="close" @click="showModal = !showModal"></button>
      </div>
    </div>
  </main>
</template>
