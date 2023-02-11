<script setup>
import { ref } from "vue";
import FileUpload from "../components/FileUpload.vue";
import { useRouter } from 'vue-router'
const adData = ref({
  name: null,
  type: null,
  description: null,
  city: null,
  zipcode: null,
  address: null,
  date: null,
  error: null
});
const fileNames = ref([]);

const dataProperties = ref({
    nbBedroom: null,
    nbBed: null,
    nbBathroom: null,
    swimingpool: null,
    kitchen: null,
    parking: null,
    airConditioning: null,
    heating: null
  })


const saveAdvertisement = () => {
  const router = useRouter()
  console.log(adData.value.date[0] + "-" + adData.value.date[1])
  if(adData.value.zipcode == null
      || adData.value.type == null
      || adData.value.description == null
      || adData.value.city == null
      || adData.value.name == null
      || adData.value.address == null
      || adData.value.date == null) {
        adData.value.error = 'Tous les champs sont obligatoires'

      return
    }

  if(adData.value.zipcode.match(/^[0-9]{5}$/) == null) {
    adData.value.error= "Code postal non valide";

    return
  }

  const requestAdvertisement = new Request(
    "https://localhost/api/advertisements",
    {
      method: "POST",
      body: JSON.stringify({
        name: adData.value.name,
        type: adData.value.type,
        description: adData.value.description,
        city: adData.value.city,
        zipcode: adData.value.zipcode,
        address: adData.value.address,
        dateStart: adData.value.date[0],
        dateEnd: adData.value.date[1],
        properties: dataProperties.value
      }),
      headers: {
        "Content-Type": "application/ld+json"
      }
    });
  fetch(requestAdvertisement)
        .then((response) => console.log('success'))
}

</script>

<template>
  <div class="container">
    <div class="card">
      <div class="card-content">
        <div class="content">
          <h2>Création d'une annonce</h2>
          <div class="columns">
            <div class="column">
              <div class="field">
                <label class="label">Titre de l'annonce</label>
                <div class="control">
                  <input class="input" v-model="adData.name" type="text" placeholder="titre de l'annonce">
                </div>
              </div>
            </div>
          </div>
          <form @submit.prevent="saveAdvertisement">
            <div class="columns">
              <div class="column">
                <div class="field">
                  <label class="label">Type de bien </label>
                  <div class="control">
                    <div class="control">
                      <label class="radio">
                        <input type="radio" v-model="adData.type" value="Maison" name="answer">
                        Maison
                      </label>
                      <label class="radio">
                        <input type="radio" v-model="adData.type" value="Appartement" name="answer">
                        Appartement
                      </label>
                      <label class="radio">
                        <input type="radio" v-model="adData.type" value="Maison d'hote" name="answer">
                        Hotel
                      </label>
                      <label class="radio">
                        <input type="radio" v-model="adData.type" name="answer">
                        Maison d'hôtes
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="columns">
              <div class="column">
                <div class="field">
                  <label class="label">Description</label>
                  <div class="control">
                    <textarea class="textarea" v-model="adData.description" placeholder="Saisir une description"></textarea>
                  </div>
                </div>
              </div>
            </div>
            <div class="columns">
              <div class="column">
                <div class="field">
                  <label>Ville</label>
                  <input v-model="adData.city" class="input" type="text">
                </div>
              </div>
              <div class="column">
                <div class="field">
                  <label>Code postal</label>
                  <input v-model="adData.zipcode" class="input" type="text" maxlength="5">
                </div>
              </div>
            </div>
            <div class="columns">
              <div class="column">
                <div class="field">
                  <label>Adresse</label>
                  <input v-model="adData.address" class="input" type="text">
                </div>
              </div>
            </div>
            <div class="columns">
              <div class="column">
                <Datepicker v-model="adData.date" range />
              </div>
            </div>
            <div class="columns">
              <div class="column">
                <FileUpload v-model:fileNames="fileNames" />
              </div>
            </div>
            <h2>Propriété</h2>
            <hr>
            <div class="columns">
              <div class="column">
                <div class="field">
                  <label class="label">Nb de chambres</label>
                  <div class="control">
                    <input v-model="dataProperties.nbBedroom" class="input" type="number">
                  </div>
                </div>
              </div>
              <div class="column">
                <div class="field">
                  <label class="label">Nb de lit</label>
                  <div class="control">
                    <input v-model="dataProperties.nbBed" class="input" type="number">
                  </div>
                </div>
              </div>
            </div>
            <div class="columns">
              <div class="column">
                <div class="field">
                  <label class="label">Nb de salle de bain</label>
                  <div class="control">
                    <input v-model="dataProperties.nbBathroom" class="input" type="number">
                  </div>
                </div>
              </div>
              <div class="column">
                <div class="field">
                  <label class="label">Parking à disposition </label>
                  <div class="control">
                    <div class="control">
                      <div class="control">
                        <label class="radio">
                          <input v-model="dataProperties.parking" value="1" type="radio" name="parking">
                          Oui
                        </label>
                        <label class="radio">
                          <input type="radio" v-model="dataProperties.parking" value="0" name="parking">
                          Non
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="columns">
              <div class="column">
                <div class="field">
                  <label class="label">Cuisine à disposition </label>
                  <div class="control">
                    <div class="control">
                      <div class="control">
                        <label class="radio">
                          <input type="radio" v-model="dataProperties.kitchen" value="1" name="kitchen">
                          Oui
                        </label>
                        <label class="radio">
                          <input type="radio" v-model="dataProperties.kitchen" value="0" name="kitchen">
                          Non
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="column">
                <div class="field">
                  <label class="label">Climatisation </label>
                  <div class="control">
                    <div class="control">
                      <div class="control">
                        <label class="radio">
                          <input type="radio" v-model="dataProperties.airConditioning" value="1" name="airConditioning">
                          Oui
                        </label>
                        <label class="radio">
                          <input type="radio" v-model="dataProperties.airConditioning" value="0" name="airConditioning">
                          Non
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="columns">
              <div class="column">
                <div class="field">
                  <label class="label">Chauffage </label>
                  <div class="control">
                    <div class="control">
                      <div class="control">
                        <label class="radio">
                          <input type="radio" v-model="dataProperties.heating" value="1" name="heating">
                          Oui
                        </label>
                        <label class="radio">
                          <input type="radio" v-model="dataProperties.heating" value="0" name="heating">
                          Non
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="column">
                <div class="field">
                  <label class="label">Piscine </label>
                  <div class="control">
                    <div class="control">
                      <div class="control">
                        <label class="radio">
                          <input type="radio" v-model="dataProperties.swimingpool" value="1" name="swimingpool">
                          Oui
                        </label>
                        <label class="radio">
                          <input type="radio" v-model="dataProperties.swimingpool" value="0" name="swimingpool">
                          Non
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <p  class="has-text-centered has-text-danger"></p>
            <div v-if="adData.error" class="notification is-danger">
              {{adData.error}}
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