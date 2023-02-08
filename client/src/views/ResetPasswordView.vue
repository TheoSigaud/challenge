<script setup>
import {onMounted, ref} from 'vue'
import {useRoute, useRouter} from 'vue-router'

const route = useRoute()
const router = useRouter()

const error = ref(null)
const data = ref({
  password: null,
  passwordConfirm: null,
  error: null
})


onMounted(() => {
  const token = route.query.token

  if (token === undefined) {
    router.push({name: 'login'})
  } else {
    fetch(`https://localhost/check-token/${token}`, {
      method: 'GET',
    })
        .then((response) => response.json())
        .then((data) => {
          if (data !== 'Success') {
            error.value = true
          }
        })
  }
})

function resetPassword() {
  data.value.error = null

  if (data.value.password === null || data.value.passwordConfirm === null) {
    data.value.error = 'Tous les champs sont obligatoires'
  }else if (data.value.password !== data.value.passwordConfirm) {
    data.value.error = 'Les mots de passe ne correspondent pas'
  }  else {
    const reset = new Request(`https://localhost/reset/password`, {
      method: 'Patch',
      body: JSON.stringify({
        password: data.value.password,
        token: route.query.token
      }),
      headers: {
        "Content-Type": "application/merge-patch+json"
      }
    })

    fetch(reset)
        .then(response => {
          router.push({name: 'login'})
        })
  }
}
</script>

<template>
  <main>
    <div class="container">
      <div v-if="!error">
        <div class="card">
          <div class="card-content">
            <div class="content">
              <div>
                <h2 class="has-text-centered">Réinitialisation du mot de passe</h2>

                <form @submit.prevent="resetPassword">
                  <div class="field">
                    <label class="label">Mot de passe</label>
                    <div class="control">
                      <input v-model="data.password" class="input" type="password" placeholder="*****">
                    </div>
                  </div>

                  <div class="field">
                    <label class="label">Confirmation du mot de passe</label>
                    <div class="control">
                      <input v-model="data.passwordConfirm" class="input" type="password" placeholder="*****">
                    </div>
                  </div>

                  <p v-if="data.error" class="has-text-centered has-text-danger">{{data.error}}</p>

                  <div class="is-flex is-justify-content-center">
                    <button class="button is-primary" type="submit">Se connecter</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>


      <div v-else>
        <div class="notification is-warning">
          <p>Le lien n'est pas valide !</p>
        </div>

        <div class="is-flex is-justify-content-center">
          <router-link to="/login" class="button is-info">Se connecter</router-link>
        </div>
      </div>
    </div>
  </main>
</template>
