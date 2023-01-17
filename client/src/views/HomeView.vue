<script setup>
  import { ref } from 'vue'
  const isRegister = ref(false)

  const registerData = ref({
    firstname: null,
    lastname: null,
    email: null,
    password: null,
    confirmPassword: null,
    error: null
  })

  function register() {
    registerData.value.error = null

    if (registerData.value.email == null
      || registerData.value.password == null
      || registerData.value.confirmPassword == null) {
      registerData.value.error = 'Tous les champs sont obligatoires'
      return
    }

    if (registerData.value.password !== registerData.value.confirmPassword) {
      registerData.value.error = 'Les mots de passe ne correspondent pas'
      return
    }
    console.log('register')
    const resultRegister = new Request(
        "http://localhost/users/register",
        {
          method: "POST",
          body: JSON.stringify({
            email: registerData.value.email,
            password: registerData.value.password
          }),
          headers: {
            "Content-Type": "application/json"
          }
        });
  }
</script>

<template>
  <main>
    <div class="container">
      <div class="card">
        <div class="card-content">
          <div class="content">
            <h2>Bienvenue</h2>

            <form v-if="!isRegister">
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

            <form v-else @submit.prevent="register">
              <div class="columns">
                <div class="column">
                  <div class="field">
                    <label class="label" for="firstname">Prénom</label>
                    <div class="control">
                      <input v-model="registerData.firstname" class="input" id="firstname" type="text" placeholder="Alexis">
                    </div>
                  </div>
                </div>

                <div class="column">
                  <div class="field">
                    <label class="label" for="lastname">Nom</label>
                    <div class="control">
                      <input v-model="registerData.lastname" class="input" id="lastname" type="text" placeholder="Smith">
                    </div>
                  </div>
                </div>
              </div>

              <div class="field">
                <label class="label" for="emailRegister">Email</label>
                <div class="control">
                  <input v-model="registerData.email" class="input" id="emailRegister" type="email" placeholder="alexsmith@gmail.com">
                </div>
              </div>

              <div class="field">
                <label class="label" for="passwordRegister">Mot de passe</label>
                <div class="control">
                  <input v-model="registerData.password" class="input" id="passwordRegister" type="password" placeholder="*****">
                </div>
              </div>

              <div class="field">
                <label class="label" for="confirmPasswordRegister">Mot de passe de confirmation</label>
                <div class="control">
                  <input v-model="registerData.confirmPassword" class="input" id="confirmPasswordRegister" type="password" placeholder="*****">
                </div>
              </div>

              <p v-if="registerData.error" class="has-text-centered has-text-danger">{{registerData.error}}</p>

              <div class="is-flex is-justify-content-center">
                <button class="button is-primary" type="submit">S'inscrire</button>
              </div>
            </form>
            <p v-if="!isRegister" class="is-size-6 has-text-centered	mt-5 is-underlined" @click="isRegister = !isRegister">Pas de compte</p>
            <p v-else class="is-size-6 has-text-centered	mt-5 is-underlined" @click="isRegister = !isRegister">Se connecter</p>
          </div>
        </div>
      </div>
    </div>
  </main>
</template>
