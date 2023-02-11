<script setup>
import { RouterView } from 'vue-router'
</script>

<template>
  <nav
    class="navbar is-fixed-top"
    role="navigation"
    aria-label="main navigation"
  >
    <div class="navbar-brand">
      <a class="" href="/">
        <img src="./assets/logo.png" width="90" height="90" />
      </a>

      <a
        role="button"
        class="navbar-burger"
        aria-label="menu"
        aria-expanded="false"
        data-target="navbarBasicExample"
      >
        <span aria-hidden="true"></span>
        <span aria-hidden="true"></span>
        <span aria-hidden="true"></span>
      </a>
    </div>

    <div id="navbarBasicExample" class="navbar-menu">
      <div class="navbar-end">
        <div class="navbar-item">
          <div class="buttons">
            <router-link class="button is-primary" to="/login">
              <strong>Se connecter</strong>
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </nav>
  <RouterView />
</template>