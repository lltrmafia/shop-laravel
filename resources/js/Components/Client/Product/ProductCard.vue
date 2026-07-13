<template>
    <div class="border border-gray-300 rounded-md overflow-hidden w-auto p-3" :key="product.id">
        <div class="flex flex-col justify-between h-full">
            <div class="px-5">
                <img :src="product.image_preview?.url" :alt="product.image_preview?.alt">
            </div>
            <div>
                <h4 class="text-md"><router-link :to="`/products/${product.slug}`">{{ product.title }}</router-link></h4>
                <p class="text-[24px] font-bold mt-3">{{ product.price }} <span class="uppercase text-sm font-medium">AED</span></p>
                <div class="flex gap-1 items-center">
                    <svg width="10" height="10">
                        <circle cx="5" cy="5" r="4" :class="stockClass(product.stock_status)"  fill="currentColor"/>
                    </svg>
                    <span :class="stockClass(product.stock_status)">
                        {{stockLabels[product.stock_status]}}
                    </span>
                </div>
                <div class="flex items-center justify-between mt-3 mb-2">
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

                    <a class="bg-blue-700 flex text-white px-4 py-2 rounded-md items-center gap-1 text-[15px]" href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                        </svg>
                        В корзину
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {defineComponent} from 'vue'

export default defineComponent({
    name: "ProductCard",

    data(){
        return{
            stockLabels: {
                in_stock: 'В наличии',
                low_stock: 'Мало',
                out_of_stock: 'Нет в наличии'
            },
            productQty: 1,
        }
    },

    props: {
        product: Object
    },

    methods: {
        stockClass(status) {
            const classes = {
                in_stock: 'text-green-600',
                low_stock: 'text-orange-500',
                out_of_stock: 'text-red-600',
            }

            return classes[status]
        }
    }
})
</script>

<style scoped>

</style>
