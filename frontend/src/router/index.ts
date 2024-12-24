import { createRouter, createWebHistory } from 'vue-router'
import Home from '@/pages/Home.vue'
import { useAuthStore } from '@/stores/auth'
import api from '@/utils/api'

const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: '/',
      name: 'Home',
      component: Home
    },
    {
      path: '/post-app',
      name: 'PostApp',
      component: () => import('@/pages/PostApp.vue'),
      meta: { requiresAuth: true }
    },
    {
      path: '/login',
      name: 'Login',
      component: () => import('@/pages/Login.vue')
    },
    {
      path: '/signup',
      name: 'Signup',
      component: () => import('@/pages/Signup.vue')
    },
    {
      path: '/verify-email',
      name: 'VerifyEmail',
      component: () => import('../pages/VerifyEmail.vue')
    }
  ]
})

router.beforeEach(async (to, from, next) => {
  const authStore = useAuthStore()
  
  if (to.meta.requiresAuth) {
    if (authStore.isAuthenticated || localStorage.getItem('token')) {
      next()
    } else {
      next({ name: 'Login', query: { redirect: to.fullPath } })
    }
  } else {
    next()
  }
})

export default router 