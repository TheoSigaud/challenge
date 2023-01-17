<script setup>
  import { ref } from 'vue'

  const registerData = ref({
    firstname: null,
    lastname: null,
    email: null,
    birthday: null,
    address: null,
    password: null,
    confirmPassword: null,
    error: null
  })

  console.log("Profile");

  // const requestRegister = new Request(
  //       "http://localhost/users/",
  //       {
  //         method: "PATCH",
  //         body: JSON.stringify({
  //           firstname: registerData.value.firstname,
  //           lastname: registerData.value.lastname,
  //           email: registerData.value.email,
  //           birthday: registerData.value.birthday,
  //           password: registerData.value.password,
  //           role: 0
  //         }),
  //         headers: {
  //           "Content-Type": "application/json"
  //         }
  //       });

</script>

<template>
  <div class="container">
    <div class="card">
      <div class="card-content">
        <div class="content">
          <h2>Profile</h2>
          <form>
            <div class="columns">
              <div class="column">
                <div class="field">
                  <label class="label">Nom</label>
                  <div class="control">
                    <input class="input" v-model="registerData.lastname"  type="text" placeholder="Your name" />
                  </div>
                </div>
              </div>
              <div class="column">
                <div class="field">
                  <label class="label">Prénom</label>
                  <div class="control">
                    <input class="input" v-model="registerData.firstname" type="text" placeholder="Your firstname" />
                  </div>
                </div>
              </div>
            </div>
            <div class="columns">
              <div class="column">
                <div class="field">
                  <label class="label">Email</label>
                  <div class="control">
                    <input class="input" v-model="registerData.email" type="email" placeholder="email@email.com" disabled/>
                  </div>
                </div>
              </div>
              <div class="column">
                <div class="field">
                  <label class="label">Date de naissance</label>
                  <div class="control">
                    <Datepicker v-model="registerData.birthday" :enable-time-picker="false"></Datepicker>
                  </div>
                </div>
              </div>
            </div>
            <div class="columns">
              <div class="column">
                <div class="field">
                  <label class="label" for="address">Adresse</label>
                  <div class="control">
                    <input v-model="registerData.address" class="input" id="address" type="text" placeholder="111 Rue de la Fontainer 75018 Paris">
                  </div>
                </div>
              </div>
            </div>
            <div class="is-flex is-justify-content-center">
              <button class="button is-primary" type="submit">Sauvegarder</button>
            </div>
          </form>
          <hr>
        </div>
      </div>
    </div>
  </div>
</template>
