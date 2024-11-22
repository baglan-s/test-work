<script setup>

import { useStore } from 'vuex'
import { ref, computed, defineModel } from 'vue'
import WalletService from '../services/WalletService.js'

const store = useStore()
const wallet = computed(() => store.getters.wallet)
const user = computed(() => store.getters.user)
const transactions = ref([])
const searchWord = defineModel('searchWord')
const paginationObj = ref({})
const pages = ref([])
const itemsLimit = 20;
const isLoading = ref(false)

function processPages() {
    pages.value = [];

    const currentPage = paginationObj.value.current_page
    const lastPage = paginationObj.value.last_page
    const delta = 2;
    for (
        let i = Math.max(1, currentPage - delta);
        i <= Math.min(lastPage, currentPage + delta);
        i++
    ) {
        pages.value.push(i);
    }
    if (pages.value[0] > 1) {
        if (pages.value[0] > 2) {
            pages.value.unshift("...");
        }
        pages.value.unshift(1);
    }
    if (pages.value[pages.value.length - 1] < lastPage) {
        if (pages.value[pages.value.length - 1] < lastPage - 1) {
            pages.value.push("...");
        }
        pages.value.push(lastPage);
    }
}

getTransactions(null, null, itemsLimit)

function getTransactions(search = null, page = null, limit = null) {
    isLoading.value = true;

    WalletService.getTransactions(search, page, limit).then(response => {
        transactions.value = response.data
        paginationObj.value = response.meta
        processPages()
        isLoading.value = false;
    }).catch(error => {
        console.log(error)
        isLoading.value = false;
    })
}

</script>

<template>
    <div class="content">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="col-md-9">
                    <div class="search-block">
                        <input type="text" v-model="searchWord" placeholder="Введите описание">
                        <button @click="getTransactions(searchWord, null, itemsLimit)" :disabled="isLoading">Поиск</button>
                    </div>
                    <div class="transactions-table mb-5" v-if="transactions.length > 0">
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
                    <div class="empty-block d-flex justify-content-center align-items-center" v-else>
                        <p class="text-center">У вас пока нет транзакций</p>
                    </div>
                    <div class="pagination d-flex align-items-center justify-content-center" v-if="paginationObj.last_page && paginationObj.last_page > 1">
                        <div class="pagination-buttons d-flex align-items-center justify-content-center">
                            <button class="bagination-btn" @click="getTransactions(searchWord, paginationObj.current_page - 1, itemsLimit)" :disabled="paginationObj.current_page == 1">
                                <i class="pi pi-chevron-left"></i>
                            </button>
                            <template v-for="page in pages" :key="page">
                                <button class="bagination-btn" :class="{active: page === paginationObj.current_page}" v-if="page === '...'" disabled>
                                    <span>{{ page }}</span>
                                </button>
                                <button class="bagination-btn" :class="{active: page === paginationObj.current_page}" @click="getTransactions(searchWord, page, itemsLimit)" v-else>
                                    <span>{{ page }}</span>
                                </button>
                            </template>
                            <button class="bagination-btn" @click="getTransactions(searchWord, paginationObj.current_page + 1, itemsLimit)" :disabled="paginationObj.current_page == paginationObj.last_page">
                                <i class="pi pi-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">

.search-block {
    display: flex;
    align-items: center;
    margin-bottom: 20px;

    input[type="text"] {
        width: 200px;
        padding: 10px 20px;
        border-radius: 5px;
        border: 1px solid #ccc;
        margin-right: 10px;
        width: 80%;
        background-color: #444;
        border: none;
        color: #f8f8f8;

        &:focus {
            outline: none;
            border: none;
        }
    }

    button {
        width: 19%;
        padding: 10px 15px;
        min-width: 100px;
        background-color: #29c784;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;

        &:disabled {
            background-color: #29A796;
        }
    }
}

.pagination-buttons {
    gap: 10px;

    button {
        width: 40px;
        height: 40px;
        border: none;
        background-color: #C1EDDB;
        border-radius: 7px;
        display: flex;
        justify-content: center;
        align-items: center;
        color: #444;
        font-weight: 600;
        
        &.active, &:disabled {
            background-color: #8BB7C4;
            cursor: default;
        }
    }
}

</style>