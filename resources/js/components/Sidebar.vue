<script setup>

import  { useStore } from 'vuex'
import  { computed } from 'vue'
import  { useRoute, useRouter } from 'vue-router'
import Avatar from '../../images/user-icon.png'
import AuthService from '../services/AuthService.js'

const store = useStore();
const route = useRoute();
const router = useRouter();
const menus = computed(() => store.getters.menus)
const user = computed(() => store.getters.user)

function logout() {
    AuthService.logout().then(response => {
        store.dispatch('clearUserData')
        router.push({name: 'login'})
    }).catch(error => console.log(error))
}

</script>

<template>
    <aside>
        <div class="menu-list">
            <ul>
                <li v-for="menu in menus" :key="menu.id">
                    <a :href="menu.link" :class="{active: menu.link === route.path}">
                        <span class="menu-icon">
                            <i :class="menu.icon"></i>
                        </span>
                        <span class="menu-text">{{ menu.name }}</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="sidebar-info d-flex flex-column">
            <div class="user-data">
                <img :src="Avatar" alt="">
                <div class="user-details d-flex flex-column justify-content-center">
                    <span>{{ user.email }}</span>
                    <p>{{ user.name }}</p>
                </div>
            </div>
            <button class="logout-btn" @click="logout">
                <i class="pi pi-sign-out"></i>
                <span>Выйти</span>
            </button>
        </div>
    </aside>
</template>

<style scoped lang="scss">

aside {
    border-right: 1px solid #444;
    min-height: calc(100vh - 129px);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    padding: 40px 30px;

    .menu-list {
        ul {
            padding-left: 0;
            list-style-type: none;

            li {
                margin-bottom: 10px;

                a {
                    color: #888;
                    display: flex;
                    align-items: center;
                    text-decoration: none;


                    &.active {
                        color: #f8f8f8;

                        span.menu-icon {
                            background-color: #29C784;
                        }
                    }

                    span.menu-icon {
                        margin-right: 20px;
                        width: 40px;
                        height: 40px;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        border-radius: 8px;
                    }
                }
            }
        }
    }

    .sidebar-info {
        .user-data {
            display: flex;
            align-items: center;
            padding: 20px;
            border: 2px solid #f8f8f8;
            border-radius: 20px;
            margin-bottom: 20px;

            img {
                width: 50px;
                margin-right: 13px;
            }

            .user-details {
                span {
                    font-size: 14px;
                    color: #888;
                    font-weight: 400;
                }

                p {
                    margin-bottom: 0;
                    font-weight: 600;
                }
            }
        }

        .logout-btn {
            background-color: #444;
            border: none;
            color: #f8f8f8;
            min-height: 45px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 10px;

            span {
                margin-left: 10px;
            }
        }
    }
}

</style>