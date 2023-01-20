import { createRouter, createWebHistory } from 'vue-router'
import LoginView from '../views/LoginView.vue'
import CreateAdvertisementView from '../views/CreateAdvertisementView.vue'
import MyListingsView from '../views/MyListingsView.vue'
import MyAdvertisementsView from '../views/MyAdvertisementsView.vue'
import ConfirmAccount from '../views/ConfirmView.vue'
import jsCookie from 'js-cookie'

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
      component: MyListingsView,
      meta: {requiresAuth: true}
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
    }
  ]
})

router.beforeEach((to, from, next) => {
  if (to.meta.requiresAuth) {
    const token = jsCookie.get('jwt')

    const requestToken = new Request(
        "https://localhost/api/auth",
        {
          method: "POST",
          headers: {
            "Authorization": "Bearer " + token
          }
        });

    fetch(requestToken)
        .then((response) => {
            if (response.status === 200) {
                next()
            } else {
                next('/login')
            }
        })
  }else {
    next()
  }
})
export default router
