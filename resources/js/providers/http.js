import axios from 'axios';

export default {
    request: axios.create({
        baseURL: import.meta.env.VITE_API_URL + '/api',
    }),

    setBearerToken(config = {}) {
        config.headers['Authorization'] = 'Bearer ' + localStorage.getItem('userToken');

        return config;
    },

    get(uri, params = {}) {
        if (params.length > 0) {
            uri = uri + '?' + new URLSearchParams(params).toString()
        }

        return this.request.get(uri, this.getDefaultHeaders())
    },

    post(uri, params = {}, config = {}) {
        if (config.headers && this.hasContentType(config.headers, 'multipart/form-data')) {
            config = this.getDefaultHeaders('multipart/form-data');
            params = this.makeFormData(params)
        } else if (!config.headers) {
            config = this.getDefaultHeaders();
        }

        return this.request.post(uri, params, config)
    },

    put(uri, params = {}, config = {}) {
        if (config.headers && this.hasContentType(config.headers, 'multipart/form-data')) {
            config = this.getDefaultHeaders('multipart/form-data');
            params = this.makeFormData(params)
        } else if (!config.headers) {
            config = this.getDefaultHeaders();
        }

        return this.request.put(uri, params, config)
    },

    delete(uri) {
        return this.request.delete(uri, this.getDefaultHeaders())
    },

    deleteForm(uri, params = {}) {
        params = this.makeFormData(params)

        let data = {
            method: 'DELETE',
            data: params,
            url: `${config.apiHost}/${uri}`,
            headers: {
                'Content-Type': 'multipart/form-data',
                'Accept': 'application/json',
            } 
        }

        return axios(data);
    },

    getDefaultHeaders(contentType = false) {
        return {
            headers: {
                'Access-Control-Allow-Origin': '*',
                'Content-Type': contentType ? contentType :'application/json',
                'Accept': 'application/json',
                'Authorization': 'Bearer ' + localStorage.getItem('userToken')
            }
        }
    },

    makeFormData(params, formData = new FormData) {
        for (const [key, value] of Object.entries(params)) {
            if (Array.isArray(value)) {
                let index = 0;

                value.forEach(item => {
                    if (item instanceof Object) {
                        for (const [itemKey, itemValue] of Object.entries(item)) {
                            formData.append(`${key}[${index}][${itemKey}]`, itemValue);
                        }
                    } else {
                        formData.append(`${key}[${index}]`, item);
                    }

                    index++;
                })
            } else {
                formData.append(key, value)
            }
        }

        return formData;
    },
    hasContentType(headers, contentType) {
        return headers['Content-Type'] && headers['Content-Type'] == contentType
    }
}