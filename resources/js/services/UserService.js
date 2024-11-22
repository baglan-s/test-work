import http from '../providers/http.js'

export default {
    authenticated() {
        return new Promise((resolve, reject) => {
            http.get(`/users/authenticated`).then(response => {
                resolve(response.data);
            }).catch(err => reject(err));
        })
    },
}