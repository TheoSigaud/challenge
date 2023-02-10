<script setup>
import { ref } from 'vue'
const registerData = ref({
    pwd: null,
    confirmPwd: null,
  })

</script>

<template>
  <div class="container">
    <div class="card ">
      <div class="card-content">
        <div class="content">
          <h2>Modification du mot de passe</h2>
          <hr>
          <form>
            <div class="columns">
              <div class="column">
                <div class="field">
                  <label class="label">Mot de passe</label>
                  <div class="control">
                    <input class="input" v-model="registerData.pwd"  type="password" placeholder="Your password" />
                  </div>
                </div>
              </div>
              <div class="column">
                <div class="field">
                  <label class="label">Ressaisir le mot de passe</label>
                  <div class="control">
                    <input class="input" v-model="registerData.confirmPwd" type="password" placeholder="Retype your new password" />
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
    </div>
  </div>
</template>