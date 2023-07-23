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

router.beforeEach((to, from, next) => {
  // Check if 'agent' key exists in local storage
  const agentData = localStorage.getItem('agent');

  if (!agentData) {
    // If 'agent' key does not exist, allow access to '/login' and '/register' routes
    if (to.name === 'login' || to.name === 'register') {
      next();
    } else {
      // Redirect to '/login' for any other route
      next({ name: 'login' });
    }
  } else {
    // If 'agent' key exists, parse the JSON and check the 'isAdmin' property
    const agent = JSON.parse(agentData);

    if (agent.isAdmin === true && to.name === 'dashboard') {
      // If 'isAdmin' is true and trying to access '/dashboard', allow access
      next();
    } else if (agent.isAdmin === false && to.name === 'profile') {
      // If 'isAdmin' is false and trying to access '/profile', allow access
      next();
    } else {
      // For any other route, redirect to '/dashboard' or '/profile' based on 'isAdmin' property
      next({ name: agent.isAdmin ? 'dashboard' : 'profile' });
    }
  }
});
export default router
