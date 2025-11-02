import { createRouter, createWebHashHistory } from 'vue-router'
import settings from '@/pages/settings/router.js'

const routes = [
    {
      path: '/login',
      name: 'login',
      component: () => import('@/pages/auth/Login.vue')
    },
    {
      path: '/register',
      name: 'register',
      component: () => import('@/pages/auth/Register.vue')
    },
    {
      path: '/forgot',
      name: 'forgot',
      component: () => import('@/pages/auth/Forgot.vue')
    },
    {
      path: '/',
      redirect: 'dashboard',
      component: () => import('@/pages/App.vue'),
      children: [
        {
          path: '/dashboard',
          name: 'dashboard',
          component: () => import('@/pages/Dashboard.vue')
        },
        {
          path: '/profile',
          name: 'profile',
          component: () => import('@/pages/Profile.vue')
        },
        ...settings,
      ]
    },

]

const router = createRouter({
  history: createWebHashHistory(),
  routes
})

export default router
