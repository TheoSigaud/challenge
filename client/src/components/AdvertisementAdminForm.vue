<script setup>
import { ref } from "vue";
import FileUpload from "./FileUpload.vue";
import router from '@/router'
import { useRoute } from 'vue-router'
import jsCookie from 'js-cookie'
import jwtDecode from 'jwt-decode'

const route = useRoute()
const { method } = defineProps({
  method: {
    type: String,
    default: "post"
  }
});
const id = ref("");
const idAd = route.query.id
let isAdmin = false
const users = ref([]);
if(route.query.id != undefined){
  isAdmin = true
}

let token = jsCookie.get('jwt')
let idUser = jwtDecode(token).id
const adData = ref({
  name: null,
  type: null,
  description: null,
  city: null,
  zipcode: null,
  address: null,
  date: null,
  price: null,
  user: null,
  error: null
});
const contentType = ref("application/ld+json");
if(method == "PATCH"){
  contentType.value = "application/merge-patch+json"
  id.value = "/"+route.query.id
}else{
  id.value = ""
  contentType.value = "application/ld+json"
}

const fileNames = ref({});

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

const fileNameEmit = (e) => {
  fileNames.value = e
}
async function base64() {
  const images = {};
  for (let i = 0; i < fileNames.value.length; i++) {
    images[fileNames.value[i].name] = await new Promise((resolve, reject) => {
      const reader = new FileReader();
      reader.readAsDataURL(fileNames.value[i]);
      reader.onload = () => resolve(reader.result);
      reader.onerror = error => reject(error);
    });
  }
  return images;
}
const saveAdvertisement = () => {
  if(adData.value.zipcode == null
      || adData.value.type == null
      || adData.value.description == null
      || adData.value.city == null
      || adData.value.name == null
      || adData.value.address == null
      || adData.value.date == null 
      || dataProperties.value.nbBedroom == null 
      || dataProperties.value.nbBedroom == "" 
      || dataProperties.value.nbBathroom == null 
      || dataProperties.value.nbBathroom == ""
      || dataProperties.value.nbBed == null 
      || dataProperties.value.nbBed== ""
      || adData.value.price == null) {
        adData.value.error = 'Tous les champs sont obligatoires'
      return
    }

    if(adData.value.date[1] == null) {
      adData.value.error = 'Vous devez sélectionner une date de début et une date de fin'

      return
    }

    if(dataProperties.value.nbBedroom < 0
      || dataProperties.value.nbBed < 0
      || dataProperties.value.nbBathroom < 0
      || adData.value.price < 0) {
      adData.value.error = 'Vous devez renseigner des nombres positifs'

      return
    }

  if(adData.value.zipcode.match(/^[0-9]{5}$/) == null) {
    adData.value.error= "Code postal non valide";

    return
  }
base64().then((data) => {
  console.log(data)
  //convert array to json
  const requestAdvertisement = new Request(
    "https://localhost/api/advertisements"+id.value,
    {
      method: method,
      body: JSON.stringify({
        name: adData.value.name,
        type: adData.value.type,
        description: adData.value.description,
        city: adData.value.city,
        zipcode: adData.value.zipcode,
        address: adData.value.address,
        dateStart: adData.value.date[0],
        dateEnd: adData.value.date[1],
        properties: dataProperties.value,
        owner: "/api/users/"+ adData.value.user,
        photo: data,
        price: adData.value.price,
      }),
      headers: {
        "Content-Type": contentType.value,
        "Authorization": "Bearer " + token
      }
    });
  fetch(requestAdvertisement)
        .then((response) => router.push({name: 'listings-advertisements'}))
  })
}


const requestAd = new Request(
  
    "https://localhost/api/users/",
    {
      method: "GET",
      headers: {
        "Content-Type": "application/Id+json",
        "Authorization": "Bearer " + token
      }
    });
  fetch(requestAd)
    .then((response) => response.json())
    .then((data) => {
      data['hydra:member'].forEach(add => users.value.push(add));
      console.log(users.value)
    })
    .catch((error) => console.log(error))

</script>


<template>
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
    <dinv class="columns">
      <div class="column">
        <div class="fiels">
          <div v-if="route.name == 'admin-create-advertisement'">
            <div class="column">
              <div class="field">
                <label class="label">Utilisateur</label>
                <select class="input" v-model="adData.user" >
                  <option value="" disabled>Choisissez un utilisateur</option>
                  <option v-for="user in users" :key="user.id" :value="user.id">{{user.firstname}} {{ user.lastname }}</option>
                </select>
              </div>
            </div>
          </div>
        </div>
      </div>
    </dinv>
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
    <div class="column">
      <div class="columns">
        <div class="filed">
          <label class="label">Prix pour une nuit</label>
            <div class="control">
              <input  class="input" type="number" v-model="adData.price">
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
        <Datepicker v-model="adData.date" :min-date="new Date()" :enable-time-picker="false" placeholder="dd/mm/yyyy - dd/mm/yyyy" range  />
      </div>
    </div>
    <div class="columns">
      <div class="column">
        <FileUpload v-model:fileNames="fileNames" @onPropsFile="fileNameEmit"/>
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
</template>