<script setup>
  import { ref } from 'vue'

  const research = ref({
    city: null,
    startDate: null,
    endDate: null
  })

  function search() {
    console.log('search')
    const requestSearch = new Request(
        "http://localhost/posts/search",
        {
          method: "POST",
          body: JSON.stringify({
            city: research.value.city,
            startDate: research.value.startDate,
            endDate: research.value.endDate
          }),
          headers: {
            "Content-Type": "application/json"
          }
        });

    fetch(requestSearch)
        .then((response) => console.log(response))
  }
</script>

<template>
  <nav
    class="navbar is-fixed-top"
    role="navigation"
    aria-label="main navigation"
  >
    <div class="navbar-brand">
      <a class="navbar-item" href="https://bulma.io">
        <img
          src="https://bulma.io/images/bulma-logo.png"
          width="112"
          height="28"
        />
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
      <div class="navbar-start" style="flex-grow: 1; justify-content: center">
        <div class="level-item">
          <div class="field has-addons">
            <p class="control">
              <input class="input" type="text" placeholder="Find a post" />
            </p>
            <p class="control">
              <Datepicker :enable-time-picker="false"></Datepicker>
            </p>
            <p class="control">
              <Datepicker :enable-time-picker="false"></Datepicker>
            </p>
            <p class="control">
              <button class="button">Search</button>
            </p>
          </div>
        </div>
      </div>

      <div class="navbar-end">
        <div class="navbar-item">
          <div class="buttons">
            <a class="button is-primary">
              <strong>Sign up</strong>
            </a>
            <a class="button is-light"> Log in </a>
          </div>
        </div>
      </div>
    </div>
  </nav>
  <div class="columns">
    <!-- <div class="column is-two-fifths"> -->
    <div class="column is-full">
      <div class="box">
        <p class="title">Flexible column</p>
        <p class="subtitle">
          This column will take up the remaining space available.
        </p>
        <div class="columns">
          <div class="column is-two-fifths">is-four-fifths</div>
          <div class="column">Auto</div>
          <div class="column">Auto</div>
        </div>
      </div>
    </div>
    <!-- <div class="column">
      <div class="box">
        <p class="title">Flexible column</p>
        <p class="subtitle">
          This column will take up the remaining space available.
        </p>
      </div>
    </div>
    <div class="column">
      <div class="box">
        <p class="title">Flexible column</p>
        <p class="subtitle">
          This column will take up the remaining space available.
        </p>
      </div>
    </div> -->
  </div>
</template>
