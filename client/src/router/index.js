import { createRouter, createWebHistory } from 'vue-router'
import LoginView from '../views/LoginView.vue'
import CreateAdvertisementView from '../views/CreateAdvertisementView.vue'
import MyListingsView from '../views/MyListingsView.vue'
import MyAdvertisementsView from '../views/MyAdvertisementsView.vue'
import ConfirmAccount from '../views/ConfirmView.vue'
import ListingsAdvertisementsView from '../views/admin/ListingsAdvertisementsView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/login',
      name: 'login',
      component: LoginView
    },
    {
      path: '/create-advertisement',
      name: 'create-advertisement',
      component: CreateAdvertisementView
    },
    {
      path: '/my-listings',
      name: 'my-listings',
      component: MyListingsView
    },
    {
      path: '/my-advertisement',
      name: 'my-advertisement',
      component: MyAdvertisementsView
    },
    {
      path: '/confirm-account',
      name: 'confirm-account',
      component: ConfirmAccount
    },
    {
      path: '/admin/listings-advertisements',
      name: 'listings-advertisements',
      component: ListingsAdvertisementsView
    },
    {
      path: '/admin/modify-advertisement',
      name: 'admin-my-advertisement',
      component: MyAdvertisementsView
    }
  ]
})

export default router
