<script setup>

import { computed } from 'vue'
import { useStore } from 'vuex'
import { useRouter, useRoute } from 'vue-router'
import Header from './components/Header.vue'
import Sidebar from './components/Sidebar.vue'
import UserService from './services/UserService.js'

const store = useStore()
const route = useRoute()
const router = useRouter()
const user = computed(() => store.getters.user)

UserService.authenticated().then(response => {
    store.dispatch('setUser', response.data)
}).catch(error => {
    console.log(error)

    if (error.status === 401) {
        store.dispatch('clearUserData')
        router.push({name: 'login'})
    }
})

</script>

<template>
    <div class="layout">
        <Header />
        <main v-if="user.id">
            <Sidebar />
            <RouterView />
        </main>
        <main v-else>
            <RouterView />
        </main>
    </div>
</template>

<style scoped lang="scss">



</style>