import { createRouter, createWebHistory } from 'vue-router'
import LoginView from '../views/LoginView.vue'
import CreateAdvertisementView from '../views/CreateAdvertisementView.vue'
import MyListingsView from '../views/MyListingsView.vue'
import MyAdvertisementsView from '../views/MyAdvertisementsView.vue'
import ConfirmAccount from '../views/ConfirmView.vue'
import ListingsAdvertisementsView from '../views/admin/ListingsAdvertisementsView.vue'
import jsCookie from 'js-cookie'
import ProfileView from '../views/ProfileView.vue'
import ResetPwdView from '../views/ResetPwdView.vue'

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
    },
    {
      path: '/profile',
      name: 'profile',
      component: ProfileView
    }, 
    {
      path: '/resetpwd',
      name: 'resetpwd',
      component: ResetPwdView
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
