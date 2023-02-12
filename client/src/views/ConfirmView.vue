<script setup>
import {onMounted, ref} from 'vue'
import {useRoute, useRouter} from 'vue-router'

const route = useRoute()
const router = useRouter()

const success = ref(null)
const error = ref(null)


onMounted(() => {
  const token = route.query.token

  if (token === undefined) {
    router.push({name: 'login'})
  } else {
    fetch(`https://localhost/confirm-account/${token}`, {
      method: 'GET',
    })
        .then((response) => response.json())
        .then((data) => {
          if (data === 'Success') {
            success.value = true
          } else {
            error.value = true
          }
        })
  }
})
</script>

<template>
  <div class="container">
    <div v-if="success">
      <p class="is-size-3">Votre compte est maintenant activé !</p>
    </div>

    <div v-if="error">
      <p class="is-size-3">Le lien n'est pas valide !</p>
    </div>

    <div class="is-flex is-justify-content-center">
      <router-link to="/login" class="button btn--lavender mt-5">
        <span>Se connecter</span>
      </router-link>
    </div>
  </div>
</template>
