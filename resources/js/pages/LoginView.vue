<script setup>

import { ref, defineModel } from 'vue'
import { useStore } from 'vuex'
import { useRouter } from 'vue-router'
import AuthService from '../services/AuthService.js'

const store = useStore()
const router = useRouter()
const loginErrors = ref([])
const isLoading = ref(false)
const email = defineModel('email')
const password = defineModel('password')
const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

function authenticate() {
    loginErrors.value = [];

    if (!validate()) {
        return false
    }

    isLoading.value = true;
    AuthService.authenticate(email.value, password.value).then(response => {
        store.dispatch('setUserToken', response.data?.token)
        store.dispatch('setUser', response.data?.user)
        isLoading.value = false;
        router.push({name: 'home'})
    }).catch(error => {
        console.log(error)
        loginErrors.value.push(error.response?.data?.data?.message ?? 'Ошибка! Попробуйте позже.')
        isLoading.value = true;
    })
}

function validate() {
    if (!email.value) {
        loginErrors.value.push('Поле электронной почты обязательно для заполнения.')
    }

    if (!password.value) {
        loginErrors.value.push('Поле пароля обязательно для заполнения.')
    }

    if (!emailPattern.test(email.value)) {
        loginErrors.value.push('Пожалуйста, введите корректный адрес электронной почты.')
    }

    if (loginErrors.value.length > 0) {
        return false
    }

    return true
}

</script>

<template>
    <div class="login-content">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="col-md-6">
                    <div class="login-form">
                        <h2 class="text-center">Войти в систему</h2>
                        <div class="form-group">
                            <label for="email">Электронная почта</label>
                            <input type="email" id="email" v-model="email" name="email" required :disabled="isLoading">
                        </div>
                        <div class="form-group">
                            <label for="password">Пароль</label>
                            <input type="password" id="password" v-model="password" name="password" required :disabled="isLoading">
                        </div>
                        <template v-if="loginErrors.length > 0">
                            <div class="d-flex flex-column">
                                <small v-for="loginError in loginErrors" :key="loginError">{{ loginError }}</small>
                            </div>
                        </template>
                        <button class="auth-btn" @click="authenticate" :disabled="isLoading">Войти</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">

.login-content {
    padding: 40px 0;
    width: 100%;
}

.login-form {
    display: flex;
    flex-direction: column;
    align-items: center;

    h2 {
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 30px;
        display: flex;
        flex-direction: column;
        width: 100%;

        label {
            margin-bottom: 7px;
            color: #f8f8f8;
        }

        input {
            height: 50px;
            border-radius: 12px;
            border: none;
            padding: 10px;
            background-color: #444;
            color: #f8f8f8;

            &:focus {
                outline: none;
                border: none;
            }
        }
    }

    small {
        color: #FA4F44;
        margin-bottom: 10px;
    }

    .auth-btn {
        border: none;
        height: 50px;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #29C784;
        color: #fff;
        width: 100%;
        border-radius: 12px;
    }
}

</style>