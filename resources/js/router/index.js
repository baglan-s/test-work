import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../pages/HomeView.vue'
import LoginView from '../pages/LoginView.vue'
import TransactionsView from '../pages/TransactionsView.vue'

function isAuthenticated(from, to, next) {
    if (localStorage.getItem('userToken')) {
        next()
    } else {
        next({ name: 'login' })
    }
}

function isGuest(from, to, next) {
    if (!localStorage.getItem('userToken')) {
        next()
    } else {
        next({ name: 'home' })
    }
}

const router = createRouter({
    history: createWebHistory(import.meta.env.VITE_BASE_URL),
    scrollBehavior(to, from, savedPosition) {
        // always scroll to top
        return { top: 0 }
    },
    routes: [
        {
            path: '/',
            name: 'home',
            component: HomeView,
            beforeEnter: [isAuthenticated]
        },
        {
            path: '/transactions',
            name: 'transactions',
            component: TransactionsView,
            beforeEnter: [isAuthenticated]
        },
        {
            path: '/login',
            name: 'login',
            component: LoginView,
            beforeEnter: [isGuest]
        },
    ]
})

export default router