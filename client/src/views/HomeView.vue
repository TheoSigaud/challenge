<script setup>
  import { ref } from 'vue'
  const isRegister = ref(false)
</script>

<template>
  <main>
    <div class="container">
      <div class="card">
        <div class="card-content">
          <div class="content">
            <h2>Bienvenue</h2>

            <form v-if="isRegister">
              <div class="field">
                <label class="label">Email</label>
                <div class="control">
                  <input class="input" type="email" placeholder="alexsmith@gmail.com">
                </div>
              </div>

              <div class="field">
                <label class="label">Mot de passe</label>
                <div class="control">
                  <input class="input" type="password" placeholder="*****">
                </div>
              </div>

              <div class="is-flex is-justify-content-center">
                <button class="button is-primary" type="submit">Se connecter</button>
              </div>
            </form>

            <form v-else>
              <div class="field">
                <label class="label">Email</label>
                <div class="control">
                  <input class="input" type="email" placeholder="alexsmith@gmail.com">
                </div>
              </div>

              <div class="field">
                <label class="label">Mot de passe</label>
                <div class="control">
                  <input class="input" type="password" placeholder="*****">
                </div>
              </div>

              <div class="field">
                <label class="label">Mot de passe de confirmation</label>
                <div class="control">
                  <input class="input" type="password" placeholder="*****">
                </div>
              </div>

              <div class="is-flex is-justify-content-center">
                <button class="button is-primary" type="submit">S'inscrire</button>
              </div>
            </form>
            <p v-if="isRegister" class="is-size-6 has-text-centered	mt-5 is-underlined" @click="isRegister = !isRegister">Pas de compte</p>
            <p v-else class="is-size-6 has-text-centered	mt-5 is-underlined" @click="isRegister = !isRegister">Se connecter</p>
          </div>
        </div>
      </div>
    </div>
  </main>
</template>
