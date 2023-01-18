import { createRouter, createWebHistory } from 'vue-router'
import LoginView from '../views/LoginView.vue'
import ConfirmAccount from '../views/ConfirmView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/login',
      name: 'login',
      component: LoginView
    },
    {
      path: '/confirm-account',
      name: 'confirm-account',
      component: ConfirmAccount
    }
  ]
})

export default router
