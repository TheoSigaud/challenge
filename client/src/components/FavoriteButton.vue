<template>
  <div class="heart-button">
    <div v-if="isInFavorites">
      <i class="fas fa-heart" @click="removeFromFavorites"></i>
    </div>
    <div v-else>
      <i class="far fa-heart" @click="addToFavorites"></i>
    </div>
  </div>
</template>

<script>
import jsCookie from "js-cookie";
import jwtDecode from "jwt-decode";

export default {
  name: 'HeartButton',
  props: {
    advertisementId: {
      type: Number,
      required: true
    },
    isInFavorites: {
      type: Boolean,
      default: false
    },
    idFav: {
      type: Number,
    },
  },
  methods: {
     async addToFavorites() {
      try {
        const token = jsCookie.get('jwt')
        const idUser = jwtDecode(token).id;
        const response = await fetch("https://localhost/api/favorites", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "Authorization": "Bearer " + token
          },
          body: JSON.stringify({
            client: "/api/users/" + idUser,
            ad: "/api/advertisements/" + this.advertisementId,
          })
        });
        if (!response.ok) {
          const data = await response.json()
        }
      } catch (error) {
        console.log(error)
      }
    },
    async removeFromFavorites() {
      try {
        const token = jsCookie.get('jwt')
        const idUser = jwtDecode(token).id;
        const response = await fetch("https://localhost/api/favorites/" + this.idFav, {
          method: "DELETE",
          headers: {
            "Content-Type": "application/json",
            "Authorization": "Bearer " + token
          },
        });
      } catch (error) {
        console.log(error)
      }
    }
  }
}
</script>

<style>
.heart-button {
  position: absolute;
  bottom: 10px;
  right: 10px;
  cursor: pointer;
}
</style>
