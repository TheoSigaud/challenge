<script>
import {ref, onMounted, watch} from 'vue';
import reviewForm from "./form/reviewForm.vue";
import reviewFormUpdate from "./form/reviewFormUpdate.vue";
import jsCookie from 'js-cookie';

export default {
  components: {
    reviewForm,
    reviewFormUpdate
  },
  data() {
    return {
      show: false
    }
  },
  methods: {
    showNotification() {
      this.show = true
    }
  },
  setup() {
    const loading = ref(false);
    const error = ref(null);
    const reservations = ref([]);
    const reload = ref(false);
    const showModal = ref(false);
    const showModalUpdate = ref(false);
    const ad_id = ref("");
    const ad_name = ref("");
    const c_id = ref("");
    const comments = ref([])
    const message = ref("");
    const title = ref("");
    const rate = ref("");
    const idcomment = ref("");

    const token = jsCookie.get('jwt')
    console.log(token)

    const requestReservations = new Request(
        "https://localhost/api/bookings",
        {
          method: "GET",
          headers: {
            "Content-Type": "application/json",
            "Authorization": "Bearer " + token
          }
        });

    const requestComments = new Request(
        "https://localhost/api/comments",
        {
          method: "GET",
          headers: {
            "Content-Type": "application/json",
            "Authorization": "Bearer " + token

          }
        });

    const reloadData = () => {
      if (reload.value === false) {
        reload.value = true
      }
    }
    const fetchData = async () => {
      loading.value = true
      try {
        const response = await fetch(requestReservations)
        const data = await response.json()
        console.log(data);
        reservations.value = data["hydra:member"].filter(item => item.client["@id"] === "/api/users/3")
      } catch (err) {
        error.value = err.message
      } finally {
        loading.value = false
      }
    }

    const getReview = async () => {
      loading.value = true;
      try {
        const response = await fetch(requestComments)
        const data = await response.json()
        comments.value = data["hydra:member"].filter(item => item.client === "/api/users/3" && item.advertisement === ad_id.value)
        message.value = comments.value[0].message;
        title.value = comments.value[0].title;
        rate.value = comments.value[0].rate;
        idcomment.value = comments.value[0].id
      } catch (err) {
        error.value = err.message
      }finally {
        loading.value = false
      }
    }

    onMounted(() => {
      fetchData()
    })

    watch(() => reload.value, async () => {
      await fetchData();
      reload.value = false;
    })

    watch(() => showModalUpdate.value, async () => {
      await getReview();
    })

    return {
      reservations,
      loading,
      error,
      reloadData,
      showModal,
      showModalUpdate,
      ad_id,
      ad_name,
      c_id,
      rate,
      message,
      title,
      idcomment
    }
  }
}
</script>

<template>
  <div v-if="loading">
    <progress class="progress is-large is-info" max="100">60%</progress>
  </div>
  <Notification v-else-if="error"
                title="ERROR"
                :message="error"
                type="danger"
  />
  <div v-else>
    <button @click="reloadData" class="button is-info">
      <ion-icon name="refresh-outline"></ion-icon>
    </button>
    <table class="table">
      <thead>
      <tr>
        <th><abbr title="Position">ID</abbr></th>
        <th>Offer n°</th>
        <th>Name of the location</th>
        <th>Description of the location</th>
        <th>Booked at</th>
        <th>Booked by</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
      </thead>
      <tbody v-for="reservation in reservations">
      <tr>
        <td>{{ reservation.id }}</td>
        <td>{{ reservation.advertisement['@id'] }}</td>
        <td>{{ reservation.advertisement.name }}</td>
        <td>{{ reservation.advertisement.description }}</td>
        <td>{{ reservation.date }}</td>
        <td>{{ reservation.client.firstname + " " + reservation.client.lastname }}</td>
        <td>{{ reservation.status }}</td>
        <td class="buttons">
          <button class="button is-primary is-light" @click="
            showModal = true;
            ad_id = reservation.advertisement['@id'];
            ad_name = reservation.advertisement.name;
            c_id = reservation.client['@id'];
          ">
            <ion-icon name="add-outline"></ion-icon>
            Add a review
          </button>
          <button class="button is-info is-light" @click="
            showModalUpdate = true;
            ad_id = reservation.advertisement['@id'];
            c_id = reservation.client['@id'];
          ">
            <ion-icon name="create-outline"></ion-icon>
            Edit
          </button>
        </td>
      </tr>
      </tbody>
    </table>
  </div>

  <div class="modal" style="display: block;" v-if="showModal">
    <div class="modal-background"></div>
    <div class="modal-card">

      <header class="modal-card-head">
        <p class="modal-card-title">Review for {{ ad_name }}</p>
        <button class="delete" aria-label="close" v-on:click="showModal = false"></button>
      </header>

      <section class="modal-card-body">
        <reviewForm
            :ad_id="ad_id"
            :c_id="c_id"
        />
      </section>

    </div>
  </div>

  <div class="modal" style="display: block;" v-if="showModalUpdate">
    <div class="modal-background"></div>
    <div class="modal-card">

      <header class="modal-card-head">
        <p class="modal-card-title">Update</p>
        <button class="delete" aria-label="close" v-on:click="showModalUpdate = false"></button>
      </header>


      <section class="modal-card-body" v-if="!loading">
        <reviewFormUpdate
            :ad_id="ad_id"
            :c_id="c_id"
            :title="title"
            :message="message"
            :rate="rate"
            :id="idcomment"
        />
      </section>

    </div>
  </div>


</template>