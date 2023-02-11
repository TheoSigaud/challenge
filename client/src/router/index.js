import { createRouter, createWebHistory } from "vue-router";
import HomeView from "../views/HomeView.vue";
import LoginView from '../views/LoginView.vue'
import ConfirmAccount from '../views/ConfirmView.vue'
import CreateAdvertisementView from "../views/CreateAdvertisementView.vue";
import MyAdvertisementsView from '../views/MyAdvertisementsView.vue'
import MyListingsView from "../views/MyListingsView.vue";
import AdvertisementView from "../views/AdvertisementView.vue";
import ListingsAdvertisementsView from "../views/admin/ListingsAdvertisementsView.vue"
import jsCookie from 'js-cookie'
import ProfileView from '../views/ProfileView.vue'
import ResetPwdView from '../views/ResetPwdView.vue'
import ResetPasswordView from "@/views/ResetPasswordView.vue";
import CheckoutView from "@/views/CheckoutView.vue";
import ListingUserView from "@/views/admin/ListingUserView.vue";
import PageNotFound from "@/views/PageNotFound.vue";
import Booking from "@/views/Booking.vue";

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
    },
    {
      path: '/reset-password',
      name: 'reset-password',
      component: ResetPasswordView
    },
    {
      path: '/checkout',
      name: 'checkout',
      component: CheckoutView
    },
    {
      path: '/admin/listings-users',
      name: 'listings-users',
      component: ListingUserView
    },
    {
      path: '/admin/update-user',
      name: 'admin-update-users',
      component: ProfileView
    },
      path: '/booking',
      name: 'booking',
      component: Booking
    },
    {
      path: "/:pathMatch(.*)*",
      component: PageNotFound
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
            return response.json()
          } else {
            next('/login')
            throw new Error('Token request failed')
          }
        })
        .then((data) => {
          next()
        })
  }else if (to.meta.requiresAuthAdmin) {
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
            return response.json()
          } else {
            next('/login')
            throw new Error('Token request failed')
          }
        })
        .then((data) => {

            if (data.data.roles.includes('ROLE_ADMIN')) {
              next()
            } else {
              next('/login')
            }
        })
  }else {
    next()
  }
})
export default router;
