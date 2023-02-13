<script setup>
import {onMounted, ref, watch} from 'vue'
import jsCookie from "js-cookie";
import ReviewForm from "../components/form/reviewForm.vue";
import jwtDecode from "jwt-decode";

const showModal = ref(false)
const showModalCreate = ref(false);
const resultBooking = ref([])
const bookingId = ref(null)
const message = ref(null)
const error = ref(null)
const ad_id = ref("");
const ad_name = ref("");
const c_id = ref("");
const comments = ref([])
const token = jsCookie.get('jwt')
const idUser = jwtDecode(token).id;
const canComment = ref(true);


onMounted(() => {
  getBookings()
})

function getBookings() {
  const request = new Request(
      "https://localhost/api/get-bookings",
      {
        method: "GET",
        headers: {
          "Content-Type": "application/json",
          "Authorization": "Bearer " + token
        }
      });

  fetch(request)
      .then(response => response.json())
      .then(data => {
        resultBooking.value = data.data
      })
}

function sendRequest() {
  if (message.value === null || bookingId.value === null) {
    error.value = 'Veuillez remplir tous les champs'
  } else {
    error.value = null

    const requestReset = new Request(
        "https://localhost/api/cancel-booking",
        {
          method: "POST",
          body: JSON.stringify({
            bookingId: bookingId.value,
            message: message.value
          }),
          headers: {
            "Content-Type": "application/json",
            "Authorization": "Bearer " + token
          }
        });
    fetch(requestReset)
        .then((response) => {
          message.value = null
          bookingId.value = null
          getBookings()
          showModal.value = !showModal;
        })
  }
}

const requestComments = new Request(
    "https://localhost/api/comments",
    {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
        "Authorization": "Bearer " + token
      }
    });

const getReview = async () => {
  try {
    const response = await fetch(requestComments)
    const data = await response.json()
    comments.value = data["hydra:member"].filter(item => item.client === "/api/users/" + idUser && item.advertisement === "/api/advertisements/" + ad_id.value)
    let bookingComment = resultBooking.value
    bookingComment = bookingComment.filter(item => item.booking.client.id === idUser && item.advertisement.id === ad_id.value)
    if (bookingComment && new Date(bookingComment[0].booking.date_end) < new Date() )
      canComment.value = false
    else canComment.value = true
  } catch (err) {
    error.value = err.message
  }
}

watch(() => showModalCreate.value, async () => {
  await getReview();
})

</script>

<template>
  <main>
    <div class="container">
      <div v-if="resultBooking.length" v-for="item in resultBooking" :key="item.booking.id" class="mb-5"
           style="width: 700px">
        <div class="card">
          <div class="card-content">
            <div class="media">
              <div class="media-left">
                <figure class="image is-128x128">
                  <img src="https://bulma.io/images/placeholders/96x96.png" alt="Placeholder image">
                </figure>
              </div>
              <div class="media-content">
                <p class="title is-4">{{ item.advertisement.name }}</p>
                <div class="content">
                  {{ item.advertisement.description }}
                </div>
              </div>
            </div>

            <div>
              <button class="button is-primary is-light" @click="
                  showModalCreate = true;
                  ad_id = item.advertisement.id;
                  ad_name = item.advertisement.name;
                ">
                <ion-icon name="add-outline"></ion-icon>
                Ajouter un commentaire
              </button>
            </div>

            <div class="is-flex is-justify-content-space-between is-align-items-center">
              <button class="button is-link is-light">Voir l'annonce</button>
              <div v-if="item.booking.status === 0">
                <button class="button is-warning is-light" @click="showModal = !showModal; bookingId = item.booking.id">
                  Faire une demande d'annulation
                </button>
              </div>

              <span v-else-if="item.booking.status === 1" class="has-text-info">La demande d'annulation a été envoyée à l'hôte</span>
              <span v-else-if="item.booking.status === -1" class="has-text-info">L'annulation a bien été prise en compte.<br>Le remboursement est en cours.</span>
            </div>
          </div>
        </div>
      </div>

      <div v-else>
        <p class="is-size-3">Aucune réservation</p>
      </div>

      <div class="modal" v-bind:class="{'is-active': showModal}">
        <div class="modal-background" @click.prevent="showModal = !showModal; error = null"></div>
        <div class="modal-content">
          <div class="card">
            <div class="card-content">
              <div class="content">
                <h3 class="mb-5">La demande sera envoyée directement à l'hôte</h3>

                <form @submit.prevent="sendRequest">
                  <textarea v-model="message" class="textarea" placeholder="Votre message" rows="10"></textarea>

                  <p v-if="error" class="has-text-centered has-text-danger">{{ error }}</p>
                  <div class="is-flex is-justify-content-center mt-6">
                    <button class="button btn--lavender" type="submit" @click="showModal = !showModal; error = null">
                      Envoyer
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <button class="modal-close is-large" aria-label="close"></button>
      </div>

      <div class="modal" style="display: block;" v-if="comments.length === 0 && showModalCreate">
        <div class="modal-background"></div>
        <div class="modal-card">

          <header class="modal-card-head">
            <p class="modal-card-title">Laisser un avis sur <b>{{ ad_name }}</b></p>
            <button class="delete" aria-label="close" v-on:click="showModalCreate = false"></button>
          </header>

          <section class="modal-card-body">
            <reviewForm
                :ad_id="ad_id"
            />
          </section>

        </div>
      </div>
      <div class="modal" style="display: block;" v-else-if="showModalCreate">
        <div class="modal-background"></div>
        <div class="modal-card">

          <header class="modal-card-head">
            <p class="modal-card-title">Avis <b>{{ ad_name }}</b></p>
            <button class="delete" aria-label="close" v-on:click="showModalCreate = false"></button>
          </header>

          <section class="modal-card-body" v-if="canComment === true">
            <p>Vous avez déjà posté ce commentaire sur cette annonce</p>
            <p class="notification is-warning" v-for="comment in comments">
              {{ comment.message }}
            </p>
          </section>
          <section class="modal-card-body" v-else>
            <p class="notification is-danger">Vous devez avoir réservé cette annonce et la date de fin de réservation doit être passée pour pouvoir poster un commentaire.</p>
          </section>

        </div>
      </div>

    </div>
  </main>
</template>
