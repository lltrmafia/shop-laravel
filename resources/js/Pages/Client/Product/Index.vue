<template>
    <ul class="flex flex-wrap gap-2">
        <li v-for="(crumb, index) in currentBreadcrumbs" :key="crumb.path">
            <router-link
                v-if="index !== currentBreadcrumbs.length - 1"
                :to="crumb.path"
                :class="['flex items-baseline gap-2 font-medium',
                index === 0 ? 'text-blue-700' : 'text-gray-400']">
                {{ crumb.title }}
                <svg v-if="currentBreadcrumbs.length >= 2" xmlns="http://www.w3.org/2000/svg" fill="none"
                     viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
                     class="relative top-[2px] size-3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
                </svg>
            </router-link>
            <span v-else class="text-gray-400 font-medium">{{ crumb.title }}</span>
        </li>
    </ul>
    <section class="flex gap-6 items-start min-h-screen mt-5">
        <div class="w-[65%] shadow-[0_8px_40px_rgba(0,0,0,0.08)] p-6 rounded-md flex items-start gap-2">
            <div>
                <img v-for="image in this.product.product_media" :key="image.url" :src="image.url" alt="image.alt">
            </div>
            <div>
                <img :src="galleryImage.url" :alt="galleryImage.alt">
            </div>
        </div>
        <div class="w-[35%] bg-transparent min-h-screen">
            <div class="shadow-[0_8px_40px_rgba(0,0,0,0.08)] p-6 rounded-md min-h-screen">
                <div>
                    <h1 class="text-black text-[26px] font-medium">{{ product.title }}</h1>
                </div>
                <div class="flex items-center justify-between">
                    <p class="text-[28px] font-bold mt-3">{{ product.price }} <span
                        class="uppercase text-sm font-medium">AED</span></p>
                    <div class="flex gap-1 items-center">
                        <svg width="10" height="10">
                            <circle cx="5" cy="5" r="4" :class="stockClass(product.stock_status)" fill="currentColor"/>
                        </svg>
                        <span :class="stockClass(product.stock_status)">
                        {{ stockLabels[product.stock_status] }}
                    </span>
                    </div>
                </div>
                <div>
                    <div class="flex items-center justify-between mt-3 mb-2 gap-2">
                        <div class="flex w-fit overflow-hidden rounded-md border border-gray-300">
                            <button @click="productQty --" :disabled="productQty <= 1"
                                    class="flex h-9 w-9 items-center justify-center border-r border-gray-300 hover:bg-gray-100">
                                −
                            </button>
                            <span class="flex h-9 min-w-9 items-center justify-center border-r border-gray-300">
                            {{ productQty }}
                        </span>
                            <button @click="productQty ++"
                                    class="flex h-9 w-9 items-center justify-center hover:bg-gray-100">
                                +
                            </button>
                        </div>

                        <a class="bg-blue-700 flex flex-1 text-white px-4 py-2 rounded-md items-center justify-center gap-1 text-[15px]"
                           href="#">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/>
                            </svg>
                            В корзину
                        </a>
                    </div>
                    <a class="block text-center bg-transparent border border-blue-700 text-blue-700 px-4 py-2 rounded-md text-[15px] w-[100%]"
                       href="#">
                        Купить в 1 клик
                    </a>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import {defineComponent} from 'vue'
import axios from "axios";
import ClientLayout from "@/Layouts/ClientLayout.vue";

export default defineComponent({
    name: "Index",
    layout: ClientLayout,

    data() {
        return {
            product: {},
            breadcrumbs: [],
            stockLabels: {
                in_stock: 'В наличии',
                low_stock: 'Мало',
                out_of_stock: 'Нет в наличии'
            },
            productQty: 1,
            galleryImage: []
        }
    },
    computed: {
        currentBreadcrumbs() {
            return [
                {
                    title: 'Главная',
                    path: '/'
                },
                {
                    title: 'Каталог',
                    path: '/catalog'
                },
                ...this.breadcrumbs,
                {
                    title: this.product.title ?? '',
                    path: ''
                }
            ]
        }
    },
    methods: {
        async loadData() {
            const {data} = await axios.get(`/api/products/${this.$route.params.product}`)
            this.product = data.product
            this.galleryImage = this.product.product_media[0]
            this.breadcrumbs = data.breadcrumbs
        },
        stockClass(status) {
            const classes = {
                in_stock: 'text-green-600',
                low_stock: 'text-orange-500',
                out_of_stock: 'text-red-600',
            }

            return classes[status]
        }
    },
    async mounted() {
        await this.loadData()
    }
})
</script>

<style scoped>

</style>
