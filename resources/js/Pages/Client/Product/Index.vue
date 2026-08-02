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
        <div class="w-[65%] shadow-[0_8px_40px_rgba(0,0,0,0.08)] rounded-md flex items-start gap-2 min-h-screen">
            <ProductGallery :images="selectedChild.product_media"/>
        </div>
        <div class="w-[35%] bg-transparent min-h-screen">
            <div class="shadow-[0_8px_40px_rgba(0,0,0,0.08)] p-6 rounded-md min-h-screen">
                <div>
                    <h1 class="text-black text-[26px] font-medium">{{ selectedChild.title }}</h1>
                </div>
                <div class="flex flex-col items-start gap-4">
                    <div v-for="variable in variableParams"
                         :key="variable.id"
                         class="flex flex-col gap-2 items-start">
                        <div>
                            <span class="text-black font-medium">{{ variable.title }}:</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <template v-for="item in variable.values"
                                      :key="item.value">
                                <button v-if="variable.input_type === 4"
                                        @click="selectChild(variable.id, item.value)"
                                        :title="item.display_name"
                                        :class="[
                                        'w-8 h-8 rounded-full flex items-center justify-center',
                                        variablesClass(variable.id, item.value)
                                            ? 'border-2 border-blue-600' : ''
                                    ]">
                                    <span
                                        class="w-6 h-6 rounded-full border border-gray-300"
                                        :style="{ backgroundColor: item.value }"
                                    ></span>
                                </button>
                                <button v-else
                                        :disabled="!isDisabled(variable.id, item.value)"
                                        @click="selectChildSize(variable.id, item.value)"
                                        :class="['w-[70px] h-[35px] rounded-md flex items-center justify-center font-medium border p-4 disabled:opacity-25',
                                        variablesClass(variable.id, item.value)
                                        ? 'border-2 border-blue-600 text-blue-700'
                                        : 'text-black border border-gray-400']"
                                        :title="item.display_name">
                                    {{ item.value }}
                                </button>
                            </template>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <p class="text-[28px] font-bold mt-3">{{ selectedChild.price }} <span
                        class="uppercase text-sm font-medium">AED</span></p>
                    <div class="flex gap-1 items-center">
                        <svg width="10" height="10">
                            <circle cx="5" cy="5" r="4" :class="stockClass(selectedChild.stock_status)"
                                    fill="currentColor"/>
                        </svg>
                        <span :class="stockClass(selectedChild.stock_status)">
                        {{ stockLabels[selectedChild.stock_status] }}
                    </span>
                    </div>
                </div>
                <div>
                    <div class="flex items-center justify-between mt-3 mb-2 gap-2">
                        <div class="flex w-fit overflow-hidden rounded-md border border-gray-300">
                            <button @click="cartItem && cartItem.qty <= 1 ? deleteItem() : decreaseQty()"
                                :disabled="!cartItem ? productQty <= 1 : 0"
                                    class="flex h-9 w-9 items-center justify-center border-r border-gray-300 hover:bg-gray-100">
                                <svg v-if="cartItem && cartItem.qty <= 1"
                                     xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4 text-red-600">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>
                                <span v-else>-</span>

                            </button>


                            <span class="flex h-9 min-w-9 items-center justify-center border-r border-gray-300">
                                {{ !cartItem ?  productQty  : cartItem.qty }}
                            </span>
                            <button @click="increaseQty"
                                    class="flex h-9 w-9 items-center justify-center hover:bg-gray-100">
                                +
                            </button>
                        </div>

                        <a v-if="!cartItem" @click.prevent="storeCart"
                            class="bg-blue-700 flex flex-1 text-white px-4 py-2 rounded-md items-center justify-center gap-1 text-[15px]"
                            href="#">
                            <svg v-if="!isLoading" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/>
                            </svg>
                            В корзину
                            <svg v-if="isLoading" class="w-8 h-4" viewBox="0 0 36 12">
                                <circle cx="6" cy="6" r="3" class="fill-current opacity-100">
                                    <animate attributeName="opacity" values="1;.3;.3;1" dur="1s" repeatCount="indefinite"/>
                                </circle>
                                <circle cx="18" cy="6" r="3" class="fill-current opacity-30">
                                    <animate attributeName="opacity" values=".3;1;.3;.3" dur="1s" repeatCount="indefinite"/>
                                </circle>
                                <circle cx="30" cy="6" r="3" class="fill-current opacity-30">
                                    <animate attributeName="opacity" values=".3;.3;1;.3" dur="1s" repeatCount="indefinite"/>
                                </circle>
                            </svg>
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
import ProductGallery from "@/Components/Client/Product/ProductGallery.vue";
import { useCartStore } from "@/stores/cart.js";

export default defineComponent({
    name: "Index",
    components: {ProductGallery},
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
            selectedChild: {},
            productQty: 1,
            galleryImage: [],
            variableParams: null,
            selectedParams: {},
            cartStore: useCartStore(),
            isLoading: false,
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
        },
        cartItem() {
            return this.cartStore.items.find(
                item => item.product_id === this.selectedChild.id
            )
        }
    },

    async mounted() {
        await this.loadData()
        await this.cartStore.loadCart()
    },
    // watch: {
    //     cartItem(newVal){
    //         console.log(newVal);
    //     }
    // },
    methods: {
        async loadData() {
            const {data} = await axios.get(`/api/products/${this.$route.params.product}`)
            this.product = data.product
            const childId = Number(this.$route.query.child)
            this.selectedChild =
                this.product.children.find(child => child.id === childId)
                ?? this.product.children[0]
            this.selectedParams = this.selectedChild.compare_params
            this.galleryImage = this.product.product_media[0]
            this.breadcrumbs = data.breadcrumbs
            this.variableParams = data.variableParams

        },
        stockClass(status) {
            const classes = {
                in_stock: 'text-green-600',
                low_stock: 'text-orange-500',
                out_of_stock: 'text-red-600',
            }

            return classes[status]
        },
        variablesClass(id, value) {
            return this.selectedChild.compare_params[id] === value
        },

        selectChild(paramId, value) {
            const tempSelection = {};
            tempSelection[paramId] = value
            const tempChildren = this.product.children
            this.selectedChild = tempChildren.find($child => {
                if (Object.entries($child.compare_params).some(([$key, $value]) => {
                    return $value === tempSelection[$key]
                }))
                    return $child
            })
            this.$router.replace({query: {child: this.selectedChild.id}})
            this.selectedParams = this.selectedChild.compare_params
        },
        selectChildSize(paramId, value) {
            const tempSelection = {...this.selectedParams};
            tempSelection[paramId] = value
            const tempChildren = this.product.children
            this.selectedChild = tempChildren.find($child => {
                if (Object.entries($child.compare_params).every(([$key, $value]) => {
                    return $value === tempSelection[$key]
                }))
                    return $child
            })
            this.$router.replace({query: {child: this.selectedChild.id}})
            this.selectedParams = this.selectedChild.compare_params
        },

        isDisabled(paramId, value) {
            const tempSelection = {...this.selectedParams};
            tempSelection[paramId] = value
            const tempChildren = this.product.children
            return tempChildren.some($child => {
                return Object.entries($child.compare_params).every(([$key, $value]) => {
                    return $value === tempSelection[$key]
                });
            })

        },
       async storeCart(){
           this.isLoading = true
           try{
               await axios.post(`/api/cart`, {
                   productEntries:{
                       product_id: this.selectedChild.id,
                       qty: this.productQty
                   }
               })
               await this.cartStore.loadCart()
               await this.cartStore.loadCount()
           } catch (error){
               console.log(error);
           } finally {
               this.isLoading = false
           }

        },

        async increaseQty() {
            if (this.cartItem) {
                await axios.patch(`/api/cart/${this.selectedChild.id}`, {
                    delta: 1
                })
                this.cartItem.qty ++
                await this.cartStore.loadCount()
            } else {
                this.productQty++
            }
        },

        async decreaseQty() {
            if (this.cartItem) {
                await axios.patch(`/api/cart/${this.selectedChild.id}`, {
                    delta: -1
                })
                this.cartItem.qty --
                await this.cartStore.loadCount()
            } else if (this.productQty > 1) {
                this.productQty--
            }
        },
        async deleteItem(){
            await axios.delete(`/api/cart/${this.selectedChild.id}`)
            await this.cartStore.loadCart()
            await this.cartStore.loadCount()
        }
    },
})
</script>

<style scoped>

</style>
