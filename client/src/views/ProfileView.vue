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

  const changePwd = ref({
    oldPwd: null,
    pwd: null,
    confirmPwd: null,
    error: null
  })
  const isActive = ref('profil')
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
  // fetch(requestRegister)
  //       .then((response) => console.log(response))

  const requestUser = new Request(
    "https://localhost/users/1",
    {
      method: "GET",
      headers: {
        "Content-Type": "application/json"
      }
    });
  fetch(requestUser)
    .then((response) => response.json())
    .then((data) => {
      registerData.value.firstname = data.firstname
      registerData.value.lastname = data.lastname
      registerData.value.email = data.email
      registerData.value.birthday = data.birthday
      registerData.value.address = data.address
    })
    .catch((error) => console.log(error))
    
    //save profil data to /users/1
    const saveProfil = () => {
      const requestRegister = new Request(
        "https://localhost/users/1",
        {
          method: "PATCH",
          body: JSON.stringify({
            firstname: registerData.value.firstname,
            lastname: registerData.value.lastname,
            birthday: registerData.value.birthday,
            address: registerData.value.address
          }),
          headers: {
            "Content-Type": "application/merge-patch+json"
          }
        });
      fetch(requestRegister)
            .then((response) => console.log(response))
    }


  const handleSubmit = (e) => {
    e.preventDefault()
    console.log(registerData.value)
    console.log(changePwd.value)
  }
</script>
<template>
  <div class="container">
    <div class="card ">
      <div class="card-content">
        <div class="content">
          <div class="tabs is-centered">
            <ul>
                <li v-bind:class="{ 'is-active': isActive == 'profil' }"><a v-on:click="isActive = 'profil'">Profil</a></li>
                <li v-bind:class="{ 'is-active': isActive == 'password' }"><a v-on:click="isActive = 'password'">Mot de passe</a></li>
            </ul>
          </div>
          <div class="tab-contents">
            <div v-if="isActive == 'profil' ">
              <div class="content is-active">
                <form @submit.prevent="saveProfil">
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
                    <button class="button is-primary"  type="submit">Sauvegarder</button>
                  </div>
                </form>
              </div>
            </div>
            <div v-if="isActive == 'password'">
              <div class="content" v-bind:class="{ 'is-active': isActive == 'password' }">
                <form>
                  <div class="columns">
                    <div class="column">
                      <div class="field">
                        <label class="label">Ancien mot de passe</label>
                        <div class="control">
                          <input class="input" v-model="changePwd.oldPwd"  type="password" placeholder="Your old password" />
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="columns">
                    <div class="column">
                      <div class="field">
                        <label class="label">Nouveau mot de passe</label>
                        <div class="control">
                          <input class="input" v-model="changePwd.pwd" type="password" placeholder="Type your new password" />
                        </div>
                      </div>
                    </div>
                    <div class="column">
                      <div class="field">
                        <label class="label">nouveau mot de passe</label>
                        <div class="control">
                          <input class="input" v-model="changePwd.confirmPwd" type="password" placeholder="Retype your new password" />
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="is-flex is-justify-content-center">
                    <button class="button is-primary" type="submit">Modifier le mot de passe</button>
                  </div>
                </form>
              </div>
            </div>
          </div>          
          <hr>
        </div>
      </div>
    </div>
  </div>
</template>
