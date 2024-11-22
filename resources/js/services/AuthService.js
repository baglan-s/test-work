import http from '../providers/http.js'

export default {
    authenticate(email, password) {
        return new Promise((resolve, reject) => {
            http.post(`/login`, {
                email: email,
                password: password,
            }).then(response => {
                resolve(response.data);
            }).catch(err => reject(err));
        })
    },
    logout() {
        return new Promise((resolve, reject) => {
            http.post(`/logout`).then(response => {
                resolve(response.data);
            }).catch(err => reject(err));
        })
    },
}