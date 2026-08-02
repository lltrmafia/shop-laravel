import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './Pages/Client/App.vue'
import '../css/app.css'
import router from "@/router/router.js";

const app = createApp(App)

app.use(createPinia())
app.use(router)

app.mount('#app')
