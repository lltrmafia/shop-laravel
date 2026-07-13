<template>
    <section class="flex justify-between items-end">
        <div class="flex flex-col gap-2">

            <ul class="flex flex-wrap gap-2">
                <li v-for="(crumb, index) in currentBreadcrumbs" :key="crumb.path">
                    <router-link
                        v-if="index !== currentBreadcrumbs.length - 1"
                        :to="crumb.path"
                        class="flex items-baseline gap-2 text-gray-400 font-medium">
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

            <div class="flex items-end gap-4">
                <h1 class="text-[36px] text-black font-medium leading-[100%]">{{ this.title }}</h1>
                <span
                    class="leading-[100%] text-gray-400 font-medium">Найдено {{
                        this.productsQty.count
                    }} {{ this.productsQty.label }}</span>
            </div>
        </div>

        <div class="flex items-center gap-20">
            <select @change="updateCatalog" v-model="sort"
                    class="border border-gray-300 rounded-md focus:outline-none focus:ring-0 focus:border-gray-300 cursor-pointer h-12">
                <option value="popular">Популярные</option>
                <option value="newest">Новинки</option>
                <option value="price_asc">Дешевле</option>
                <option value="price_desc">Дороже</option>
            </select>
            <div class="flex items-center gap-2">
                <div @click="view = 'grid'"
                     :class="viewButtonClass('grid')">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 0 1-1.125-1.125M3.375 19.5h7.5c.621 0 1.125-.504 1.125-1.125m-9.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75h-7.5A1.125 1.125 0 0 1 12 18.375m9.75-12.75c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125m19.5 0v1.5c0 .621-.504 1.125-1.125 1.125M2.25 5.625v1.5c0 .621.504 1.125 1.125 1.125m0 0h17.25m-17.25 0h7.5c.621 0 1.125.504 1.125 1.125M3.375 8.25c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125m17.25-3.75h-7.5c-.621 0-1.125.504-1.125 1.125m8.625-1.125c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125M12 10.875v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 10.875c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125M13.125 12h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125M20.625 12c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5M12 14.625v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 14.625c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125m0 1.5v-1.5m0 0c0-.621.504-1.125 1.125-1.125m0 0h7.5"/>
                    </svg>
                </div>
                <div @click="view = 'table'"
                     :class="viewButtonClass('table')">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/>
                    </svg>
                </div>
            </div>
        </div>
    </section>
    <div class="flex justify-between gap-10 mt-5">
        <section class="w-1/5">
            <aside class="bg-white h-screen p-4 rounded-md border border-gray-300">
                <ul>
                    <li v-for="category in categories">
                        <router-link :to="`/catalog/${category.slug}`">{{ category.title }}</router-link>
                    </li>
                </ul>
                <ProductFilter
                    :params="filterParams"
                    @filter="onFilterChange"/>
            </aside>
        </section>
        <section class="flex-1 relative min-h-screen">
            <div
                v-if="isLoading"
                class="absolute inset-0 bg-white/80 flex items-center justify-center z-50">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                     stroke="currentColor" class="size-8 animate-spin">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99"/>
                </svg>

            </div>
            <div class="grid grid-cols-4 gap-4">
                <ProductCard
                    v-for="product in products"
                    :product="product"
                    :key="product.id"/>
            </div>
        </section>
    </div>
</template>

<script>
import {defineComponent} from 'vue'
import axios from "axios";
import ClientLayout from "@/Layouts/ClientLayout.vue";
import ProductCard from "@/Components/Client/Product/ProductCard.vue";
import ProductFilter from "@/Components/Client/Filter/ProductFilter.vue";

export default defineComponent({
    name: "Index",
    layout: ClientLayout,

    components: {
        ProductFilter,
        ProductCard,
    },

    data() {
        return {
            title: '',
            products: [],
            categories: [],
            filterParams: [],
            breadcrumbs: [],
            filterData: {},
            isLoading: true,
            sort: 'popular',
            productsQty: {},
            view: 'grid',
        }
    },
    computed: {
        isCategory() {
            return !!this.$route.params.category
        },
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
                ...this.breadcrumbs
            ]
        }
    },

    async mounted() {
        const {data} = await axios.get('/api/categories')
        this.categories = data.data
        await this.loadData();
        this.isLoading = false;
    },

    methods: {
        async loadData() {
            const {data} = await axios.get(this.getApiUrl())

            this.title = data.title
            this.filterParams = data.params
            this.breadcrumbs = data.breadcrumbs
            this.products = data.products
            this.productsQty = data.productsQty
        },
        onFilterChange(filters) {
            this.filterData = filters
            this.updateCatalog()
        },
        async updateCatalog() {
            this.isLoading = true
            try {
                const {data} = await axios.get(this.getApiUrl(), {
                    params: {
                        filter: this.filterData,
                        sort: this.sort
                    }
                });
                this.products = data.products;
            } catch (e) {
                console.error(e)
            } finally {
                this.isLoading = false
            }
        },

        getApiUrl() {
            return this.isCategory
                ? `/api/categories/${this.$route.params.category}`
                : '/api/catalog'
        },
        viewButtonClass(type){
            return [
                'border rounded-md h-12 w-14 flex items-center justify-center cursor-pointer',
                this.view === type
                    ? 'border-blue-700 text-blue-700'
                    : 'border-gray-300 text-gray-500'
            ]
        },
    },
    watch: {
        async '$route.params.category'() {
            await this.loadData();
            this.sort = 'popular'
            this.filterData = {}
        }
    }
});
</script>

<style>

</style>
