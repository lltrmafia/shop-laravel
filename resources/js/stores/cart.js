import { defineStore } from 'pinia'
import axios from 'axios'

export const useCartStore = defineStore('cart', {

    state: () => ({
        count: 0,
        items: []
    }),

    actions: {

        async loadCount() {

            const response = await axios.get('/api/cart/count')

            this.count = response.data.count
        },

        async loadCart(){
            const response = await axios.get('/api/cart')
            this.items = response.data.items
        }

    }

})
