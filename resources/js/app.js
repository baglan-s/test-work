import { createApp } from 'vue'
import App from './App.vue'

import '../scss/main.scss'
import * as bootstrap from 'bootstrap'
import 'primeicons/primeicons.css'
import router from './router'
import store from './store/index.js'

const app = createApp(App)

app.use(router)
app.use(store)

app.mount('#app')