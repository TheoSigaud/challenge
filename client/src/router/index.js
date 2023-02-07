import { createRouter, createWebHistory } from "vue-router";
import HomeView from "../views/HomeView.vue";
import LoginView from '../views/LoginView.vue'
import ConfirmAccount from '../views/ConfirmView.vue'
import CreateAdvertisementView from "../views/CreateAdvertisementView.vue";
import MyListingsView from "../views/MyListingsView.vue";
import AdvertisementView from "../views/AdvertisementView.vue";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: "/login",
      name: "login",
      component: LoginView,
    },
    {
      path: "/",
      name: "home",
      component: HomeView,
    },
    {
      path: "/advertisement/:id",
      name: "advertisement",
      component: AdvertisementView,
    },
    {
      path: "/create-advertisement",
      name: "create-advertisement",
      component: CreateAdvertisementView,
    },
    {
      path: "/my-listings",
      name: "my-listings",
      component: MyListingsView,
    },
    {
      path: '/confirm-account',
      name: 'confirm-account',
      component: ConfirmAccount
    }
  ]
})

export default router;
