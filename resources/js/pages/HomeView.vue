<script setup>

import { useStore } from 'vuex'
import { ref, computed } from 'vue'
import MasterCartImage from '../../images/mastercard.png'
import WalletService from '../services/WalletService.js'

const store = useStore()
const wallet = computed(() => store.getters.wallet)
const user = computed(() => store.getters.user)
const transactions = ref([])


getWalletData()
getTransactions()

const dataInterval = setInterval(() => {
    getWalletData()
    getTransactions()
}, 30000)

function getWalletData() {
    WalletService.getWallet().then(response => {
        store.dispatch('setWallet', response.data)
    }).catch(error => console.log(error))
}

function getTransactions() {
    WalletService.getTransactions(null, null, 5).then(response => {
        transactions.value = response.data
    }).catch(error => console.log(error))
}

</script>

<template>
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <a href="#" class="cards-link">
                        <span class="cards-link__title">Мои счета</span>
                        <span class="cards-link__icon">
                            <i class="pi pi-ellipsis-h"></i>
                        </span>
                    </a>
                    <div class="user-cards">
                        <div class="user-card">
                            <div class="user-card__header">
                                <p class="user-card__header-name">{{ user.name }}</p>
                                <img :src="MasterCartImage" alt="">
                            </div>
                            <div class="user-card__balance">
                                <p class="user-card__balance-title">Баланс:</p>
                                <p class="user-card__balance-amount">${{ wallet.balance }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <a href="/transactions" class="transactions-link">
                        <span class="transactions-link__title">Последние транзакции</span>
                        <span class="transactions-link__icon">
                            <i class="pi pi-arrow-right"></i>
                        </span>
                    </a>
                    <div class="transactions-table">
                        <div class="transaction" v-for="transaction in transactions" :key="transaction.id">
                            <div class="transaction-details">
                                <div class="transaction-details__icon income" v-if="transaction.type === 'income'">
                                    <i class="pi pi-arrow-up"></i>
                                </div>
                                <div class="transaction-details__icon" v-else>
                                    <i class="pi pi-arrow-down"></i>
                                </div>
                                <div class="transaction-details__info">
                                    <p class="transaction-details__info-description">{{ transaction.description ?? 'Без описания' }}</p>
                                    <p class="transaction-details__info-date">{{ transaction.created_at }}</p>
                                </div>
                            </div>
                            <div class="transaction-balance">
                                <span class="transaction-balance__amount">${{ transaction.amount }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">

.cards-link, .transactions-link {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 30px;
    text-decoration: none;

    &__title {
        color: #f8f8f8;
        font-weight: 600;
    }

    &__icon {
        color: #f8f8f8;
        width: 40px;
        height: 40px;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #444;
        border-radius: 8px;
    }
}

.user-cards {
    .user-card {
        width: 100%;
        padding: 30px;
        background-color: #29C784;
        border-radius: 50px;

        &__header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 30px;

            &-name {
                color: #fff;
                font-weight: 600;
                font-size: 18px;
                margin-bottom: 0;
            }

            img {
                width: 60px;
            }
        }

        &__balance {
            &-title {
                color: #fff;
                font-weight: 400;
                font-size: 15px;
                margin-bottom: 5px;
            }

            &-amount {
                color: #fff;
                font-weight: 600;
                font-size: 36px;
            }
        }
    }
}

</style>