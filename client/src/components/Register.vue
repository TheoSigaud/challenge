<script setup>
  import {ref} from "vue";

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
  });

  function register() {
    registerData.value.error = null
    registerData.value.success = null

    const date = new Date(registerData.value.birthday);

    const today = new Date();
    let age = today.getFullYear() - date.getFullYear();
    const m = today.getMonth() - date.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < date.getDate())) {
      age--;
    }

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

    if (registerData.value.password.length < 8) {
      registerData.value.error = 'Le mot de passe doit contenir au moins 8 caractères.'
      return
    }

    // if (age < 15 || age > 110) {
    //   registerData.value.error = 'Vous devez avoir minimum 12 ans.'
    //   return
    // }

    const requestRegister = new Request(
        "https://kaitokid.fr/api/users",
        {
          method: "POST",
          body: JSON.stringify({
            firstname: registerData.value.firstname,
            lastname: registerData.value.lastname,
            email: registerData.value.email,
            address: registerData.value.address,
            birthday: registerData.value.birthday,
            password: registerData.value.password
          }),
          headers: {
            "Content-Type": "application/json"
          }
        });

    fetch(requestRegister)
        .then(response => {
          if (response.status === 201) {
            Object.keys(registerData.value).forEach(key => {
              registerData.value[key] = null;
            });
            registerData.value.success = 'Votre compte a bien été créé. Vérifiez vos mails pour confirmer votre compte.'
            registerData.value.error = null
          } else {
            return response.json()
          }
        })
        .then(data => {
          if (data.violations) {
            if (data.violations[0]['propertyPath'] === 'birthday') {
              registerData.value.error = 'Vous devez avoir minimum 12 ans.'
            } else {
              registerData.value.error = 'Les informations saisies sont incorrectes'
            }
          }else {
            registerData.value.error = 'Cet email est déjà utilisé.'
          }
        })
  }
</script>

<template>
  <form @submit.prevent="register">
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
      <Datepicker v-model="registerData.birthday" :enable-time-picker="false" placeholder="dd/mm/yyyy"></Datepicker>
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
      <button class="button btn--lavender" id="btn-register" type="submit">S'inscrire</button>
    </div>
  </form>
</template>