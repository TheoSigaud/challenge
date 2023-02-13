<script setup>
  import { ref } from 'vue'
  import { useRouter } from 'vue-router'
  import jsCookie from "js-cookie";

  const router = useRouter()

  const loginData = ref({
    email: null,
    password: null,
    error: null
  });

  function login() {
    loginData.value.error = null

    if (loginData.value.email === null || loginData.value.password === null) {
      loginData.value.error = 'Tous les champs sont obligatoires'

      return
    }

    const requestLogin = new Request(
        "https://localhost/api/login",
        {
          method: "POST",
          body: JSON.stringify({
            email: loginData.value.email,
            password: loginData.value.password
          }),
          headers: {
            "Content-Type": "application/json"
          }
        });

    fetch(requestLogin)
        .then((response) => response.json())
        .then((data) => {
          if (data.token) {
            jsCookie.set('jwt', data.token, { expires: 1 })
            router.push({ name: 'home' })
          } else if (data.message === 'Not confirmed') {
            loginData.value.error = 'Votre compte n\'a pas été confirmé. Vérifiez vos mails.'
          } else {
            loginData.value.error = 'Email ou mot de passe incorrect'
          }
        })
  }
</script>

<template>
  <form @submit.prevent="login">
    <div class="field">
      <label class="label">Email</label>
      <div class="control">
        <input v-model="loginData.email" class="input" type="email" placeholder="alexsmith@gmail.com">
      </div>
    </div>

    <div class="field">
      <label class="label">Mot de passe</label>
      <div class="control">
        <input v-model="loginData.password" class="input" type="password" placeholder="*****">
      </div>
    </div>

    <p v-if="loginData.error" class="has-text-centered has-text-danger">{{loginData.error}}</p>

    <div class="is-flex is-justify-content-center">
      <button id="btn-login" class="button btn--lavender" type="submit">Se connecter</button>
    </div>
  </form>
</template>

<script>
export default {
  name: "Login"
}
</script>