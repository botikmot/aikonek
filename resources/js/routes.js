import { createRouter, createWebHashHistory } from 'vue-router'
import Home from './components/Home.vue'

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home
  },
  {
    path: '/login',
    name: 'Login',
    component: () => import(/* webpackChunkName: "about" */ './components/layouts/LoginRegister.vue')
  },
  {
    path: '/register',
    name: 'Register',
    //component: () => import(/* webpackChunkName: "about" */ './components/auth/Register.vue')
  },
  {
    path: '/dashboard',
    name: 'Dashboard',
    //component: () => import(/* webpackChunkName: "about" */ './components/dashboard/Dashboard.vue')
  }
]

const router = createRouter({
  history: createWebHashHistory(),
  routes
})

export default router
