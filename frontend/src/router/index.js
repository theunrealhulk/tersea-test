import { createRouter, createWebHistory } from 'vue-router'
import login from '../views/login.vue'
import dashboard from '../views/dashboard.vue'
import profile from '../views/profile.vue'
import register from '../views/register.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'login',
      component: login
    },
    {
      path: '/dashboard',
      name: 'dashboard',
      component: dashboard
    },
    {
      path: '/profile',
      name: 'profile',
      component: profile
    },
    {
      path: '/register',
      name: 'register',
      component: register
    }
  ]
})

export default router
