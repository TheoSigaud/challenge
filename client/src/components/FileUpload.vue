<script setup>
import { ref } from "vue";
const fileNames = ref([]);

function handleFileChange(e) {
  let files = e.target.files;
  console.log(files.length);
  for (let i = 0; i < files.length; i++) {
    if (files[i].name.endsWith(".png") || files[i].name.endsWith(".jpg") || files[i].name.endsWith(".jpeg")) {
      console.log(files[i].name);
      fileNames.value.push(files[i]);
    }else{
      e.target.value = null;
    }
  }
}

</script>

<template>
  <div class="field">
    <label class="label">Photos</label>
    <div class="file has-name is-fullwidth">
      <label class="file-label">
        <input
          class="file-input"
          type="file"
          ref="fileInput"
          accept="image/*"
          @change="handleFileChange"
          :multiple="true"
        />
        <span class="file-cta">
          <span class="file-icon">
            <!-- <i class="fas fa-upload"></i> -->
          </span>
          <span class="file-label">Choisir des images</span>
        </span>
        <span class="file-name" v-if="fileNames.length">
          {{ fileNames.length }} {{ fileNames.length === 1 ? "image" : "images" }} sélectionnées
        </span>
      </label>
    </div>
  </div>
</template>