import { createApp } from 'vue'

import App from './Pages/Client/App.vue'
import '../css/app.css'
import router from "@/router/router.js";


createApp(App).use(router).mount('#app')
