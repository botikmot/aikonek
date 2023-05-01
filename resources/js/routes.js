import { createRouter, createWebHashHistory } from 'vue-router'
import Home from './components/Home.vue'
import Profile from './components/layouts/Profile.vue'
const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home
  },
  {
    path: '/profile',
    name: 'Profile',
    component: Profile
  },
]

const router = createRouter({
  history: createWebHashHistory(),
  routes
})

export default router
