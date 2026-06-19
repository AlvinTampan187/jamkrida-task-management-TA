import { createRouter, createWebHashHistory } from 'vue-router'
import settings from '@/pages/settings/router.js'
import DetailTugas from '@/pages/Detail.vue'
import PrintTugas from '@/pages/PrintTugas.vue'

const routes = [

  

  // redirect rute 
  {
    path: '/',
    redirect: '/login'
  },

  

  // AUTH
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
    path: '/print/:id',
    name: 'print-tugas',
    component: PrintTugas
  },
  {
    path: '/print-kinerja',
    name: 'print-kinerja',
    component: () => import('@/pages/PrintKinerja.vue')
  },



  // LAYOUT ATASAN
  {
    path: '/atasan',
    component: () => import('@/components/LayoutAtasan.vue'),
    children: [
      {
        path: 'dashboard',
        name: 'dashboard-atasan',
        component: () => import('@/pages/Dashboard0.vue')
      },
      {
        path: 'kinerja',
        name: 'kinerja',
        component: () => import('@/pages/Kinerja.vue')
      },
      {
        path: 'detail-tugas/:id',
        name: 'detail-tugas',
        component: () => import('@/pages/Detail.vue')
      },
      {
        path: 'form',
        name: 'form',
        component: () => import('@/pages/Form.vue')
      }
    ]
  },


  // LAYOUT KARYAWAN
  {
    path: '/karyawan',
    component: () => import('@/components/LayoutKaryawan.vue'),
    children: [
      {
        path: 'dashboard',
        name: 'dashboard-karyawan',
        component: () => import('@/pages/Dashboard0.vue')
      },
      {
        path: 'detail-tugas/:id',
        name: 'detail-tugas-karyawan',
        component: () => import('@/pages/Detail.vue')
      }
    ]
  },
 
  

  // PROFILE
  {
    path: '/profile',
    name: 'profile',
    component: () => import('@/pages/Profile.vue')
  },

  ...settings

]

const router = createRouter({
  history: createWebHashHistory(),
  routes
})

export default router