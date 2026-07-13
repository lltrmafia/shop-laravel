<template>
    <section class="flex justify-between items-center">
        <div v-if="breadcrumbs">
            <ul class="flex flex-wrap gap-2">
                <li>
                    <router-link to="/catalog"
                                 class="flex items-baseline gap-2 text-gray-400 font-medium">
                        Каталог
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                             stroke="currentColor" class="relative top-[2px] size-3">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="m5.25 4.5 7.5 7.5-7.5 7.5m6-15 7.5 7.5-7.5 7.5"/>
                        </svg>
                    </router-link>
                </li>
                <li v-for="(crumb, index) in breadcrumbs" :key="crumb.slug">
                    <router-link
                        v-if="index !== breadcrumbs.length - 1"
                        :to="`/catalog/${crumb.slug}`"
                        class="flex items-baseline gap-2 text-gray-400 font-medium">
                        {{ crumb.title }}
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                             stroke="currentColor" class="relative top-[2px] size-3">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="m5.25 4.5 7.5 7.5-7.5 7.5m6-15 7.5 7.5-7.5 7.5"/>
                        </svg>
                    </router-link>
                    <span class="text-gray-400 font-medium" v-else>{{ crumb.title }}</span>
                </li>
            </ul>
        </div>
        <div v-else>
            <ul class="flex flex-wrap gap-2">
                <li>
                    <router-link to="/catalog"
                                 class="flex items-baseline gap-2 text-gray-400 font-medium">
                        Каталог
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                             stroke="currentColor" class="relative top-[2px] size-3">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="m5.25 4.5 7.5 7.5-7.5 7.5m6-15 7.5 7.5-7.5 7.5"/>
                        </svg>
                    </router-link>
                </li>
            </ul>
        </div>
        <div>
            <select @change="updateCatalog" v-model="sort"
                class="border border-gray-300 rounded-md focus:outline-none focus:ring-0 focus:border-gray-300 cursor-pointer">
                <span>Сортировка:</span>
                <option value="popular">Популярные</option>
                <option value="newest">Новинки</option>
                <option value="price_asc">Дешевле</option>
                <option value="price_desc">Дороже</option>
            </select>
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
                    :key="product.id" />
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
        ProductCard,
        ProductFilter
    },
    data() {
        return {
            products: [],
            categories: [],
            breadcrumbs: [],
            filterParams: [],
            filterData: {},
            isLoading: true,
            sort: 'popular',
        }
    },
    async mounted() {
        const categories = await axios.get('/api/categories')
        this.categories = categories.data.data
        await this.loadData()
        this.isLoading = false
    },
    methods: {
        async loadData() {
            const {data} = await axios.get(`/api/categories/${this.$route.params.category}`)

            this.filterParams = data.params
            this.breadcrumbs = data.breadcrumbs
            this.products = data.products
        },
        onFilterChange(filters) {
            this.filterData = filters
            this.updateCatalog()
        },
        async updateCatalog() {
            this.isLoading = true
            try {
                const {data} = await axios.get(`/api/categories/${this.$route.params.category}`, {
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


        }
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
