import http from '../providers/http.js'

export default {
    getWallet() {
        return new Promise((resolve, reject) => {
            http.get(`/wallet`).then(response => {
                resolve(response.data);
            }).catch(err => reject(err));
        })
    },
    getTransactions(search = null, page = null, limit = null) {
        let filters = {}
        if (search) {
            filters.search = search
        }

        if (page) {
            filters.page = page
        }

        if (limit) {
            filters.limit = limit
        }

        return new Promise((resolve, reject) => {
            http.get(`/transactions?${new URLSearchParams(filters).toString()}`).then(response => {
                resolve(response.data);
            }).catch(err => reject(err));
        })
    }
}