<script setup>
  import { ref } from 'vue'

  const isRegister = ref(false)
  const registerData = ref({
    firstname: null,
    lastname: null,
    email: null,
    birthday: null,
    address: null,
    password: null,
    confirmPassword: null,
    error: null,
    success: null
  })
  const loginData = ref({
    email: null,
    password: null,
    error: null
  })

  function register() {
    registerData.value.error = null
    registerData.value.success = null

    if (registerData.value.email === null
      || registerData.value.password === null
      || registerData.value.confirmPassword === null) {
      registerData.value.error = 'Tous les champs sont obligatoires'

      return
    }

    if (registerData.value.password !== registerData.value.confirmPassword) {
      registerData.value.error = 'Les mots de passe ne correspondent pas'
      return
    }

    const requestRegister = new Request(
        "https://localhost/users",
        {
          method: "POST",
          body: JSON.stringify({
            firstname: registerData.value.firstname,
            lastname: registerData.value.lastname,
            email: registerData.value.email,
            address: registerData.value.address,
            birthday: registerData.value.birthday,
            password: registerData.value.password,
            roles: []
          }),
          headers: {
            "Content-Type": "application/json"
          }
        });

    fetch(requestRegister)
        .then(response => {
          console.log(response.status)
          if (response.status === 201) {
            registerData.value.success = 'Votre compte a bien été créé. Vérifiez vos mails pour confirmer votre compte.'
            registerData.value.error = null
          } else {
            registerData.value.error = 'Les informations saisies sont incorrectes. Il se peut que l\'email soit déjà utilisé.'
          }
        })
  }

  function login() {
    loginData.value.error = null

    if (loginData.value.email === null || loginData.value.password === null) {
      loginData.value.error = 'Tous les champs sont obligatoires'

      return
    }

    const requestLogin = new Request(
        "https://localhost/login",
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
          if (data.message === 'Success') {
            console.log('Success')
            // localStorage.setItem('token', data.token)
            // localStorage.setItem('user', JSON.stringify(data.user))
            // window.location.href = '/'
          } else {
            loginData.value.error = 'Email ou mot de passe incorrect'
          }
        })
  }
</script>

<template>
  <main>
    <div class="container">
      <div class="card">
        <div class="card-content">
          <div class="content">
            <h2>Bienvenue</h2>

            <form v-if="!isRegister" @submit.prevent="login">
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
                <label class="label">Date de naissance</label>
                <Datepicker v-model="registerData.birthday" :enable-time-picker="false"></Datepicker>
              </div>

              <div class="field">
                <label class="label" for="address">Adresse</label>
                <div class="control">
                  <input v-model="registerData.address" class="input" id="address" type="text" placeholder="111 Rue de la Fontainer 75018 Paris">
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
              <p v-if="registerData.success" class="has-text-centered has-text-success">{{registerData.success}}</p>

              <div v-if="!registerData.success" class="is-flex is-justify-content-center">
                <button class="button is-primary" type="submit">S'inscrire</button>
              </div>
            </form>
            <p v-if="!isRegister" class="is-size-6 has-text-centered mt-5 is-underlined is-clickable" @click="isRegister = !isRegister">Pas de compte</p>
            <p v-else class="is-size-6 has-text-centered	mt-5 is-underlined is-clickable" @click="isRegister = !isRegister">Se connecter</p>
          </div>
        </div>
      </div>
    </div>
  </main>
</template>
