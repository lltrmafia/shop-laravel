<template>
    <div>
        <Link :href="route('admin.products.create')" class="bg-gray-900 inline-block text-white px-4 py-3 rounded-lg">
            Создать
        </Link>
        <Link :href="route('admin.products.trash')"
              class="inline-block text-gray-500 px-4 py-3 hover:underline">
            Корзина ({{ trashedProducts.data.length }})
        </Link>
    </div>

    <div class="relative overflow-x-auto mt-4 rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-white">
            <thead class="text-sm text-body bg-white/20 border-b border-white/20">
            <tr>
                <th scope="col" class="px-6 py-3 font-semibold">
                    Название
                </th>
                <th scope="col" class="px-6 py-3 font-semibold">
                    ID
                </th>
                <th scope="col" class="px-6 py-3 font-semibold">
                    Слаг
                </th>
                <th scope="col" class="px-6 py-3 font-semibold">
                    Категория
                </th>
                <th scope="col" class="px-6 py-3 font-semibold">
                    Группа продуктов
                </th>
                <th scope="col" class="px-6 py-3 font-semibold">
                    Превью
                </th>
                <th scope="col" class="px-6 py-3 font-semibold">
                    Галерея
                </th>
                <th scope="col" class="px-6 py-3 font-semibold">
                    Контент
                </th>
                <th scope="col" class="px-6 py-3 font-semibold">
                    Действие
                </th>
                <th scope="col" class="px-6 py-3 font-semibold">
                    Вариации
                </th>
            </tr>
            </thead>
            <tbody>
            <template :key="item.id" v-for="item in productsData.data">

                <tr class="text-sm text-body bg-[#101828] border-b border-white/20">
                    <td class="px-6 py-4">
                        {{ item.title }}
                    </td>
                    <td class="px-6 py-4">
                        {{ item.id }}
                    </td>
                    <td class="px-6 py-4">
                        {{ item.slug }}
                    </td>
                    <td class="px-6 py-4">
                        {{ item.category_name }}
                    </td>
                    <td class="px-6 py-4">
                        {{ item.product_group_name }}
                    </td>
                    <td class="px-6 py-4 max-w-5">
                        <img :src="item.image_preview?.url" :alt="item.image_preview?.alt ?? null">
                    </td>
                    <td class="px-6 py-4 max-w-5">
                        <div class="flex gap-1">
                            <img v-for="image in item.product_gallery"
                                 :src="image?.url"
                                 :alt="image?.alt ?? null"
                                 class="w-8 h-8 object-contain">
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="prose prose-invert" v-html="item.content"></div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2 items-center">
                            <Link :href="route('admin.products.children.create', item.slug)">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                     stroke="currentColor" class="size-4 cursor-pointer text-emerald-600">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                                </svg>
                            </Link>
                            <Link :href="route('admin.products.show', item.slug)">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                     stroke="currentColor" class="size-4 cursor-pointer text-emerald-600">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                </svg>
                            </Link>
                            <Link :href="route('admin.products.edit', item.slug)">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                     stroke="currentColor" class="size-4 cursor-pointer text-emerald-600">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"/>
                                </svg>
                            </Link>
                            <svg @click="deleteProduct(item)" xmlns="http://www.w3.org/2000/svg" fill="none"
                                 viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="size-4 cursor-pointer text-red-600">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                            </svg>
                        </div>
                    </td>
                    <td>
                        <div v-if="item.children_count > 0" class="flex justify-center cursor-pointer">
                            <svg v-if="!item.isOpened" @click="loadChildren(item);" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="m11.99 16.5 3.75 3.75m0 0 3.75-3.75m-3.75 3.75V3.75H4.49"/>
                            </svg>
                            <svg v-if="item.isOpened" @click.stop="closeChildren(item);" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m11.99 7.5 3.75-3.75m0 0 3.75 3.75m-3.75-3.75v16.499H4.49" />
                            </svg>
                        </div>
                    </td>
                </tr>


                <tr v-if="item.children && item.isOpened" v-for="child in item.children.data" class="text-sm text-body bg-[#101828] border-b border-white/20">
                    <td class="px-6 py-4">
                        {{ child.title }}
                    </td>
                    <td class="px-6 py-4">
                        {{ child.id }}
                    </td>
                    <td class="px-6 py-4">
                        {{ child.slug }}
                    </td>
                    <td class="px-6 py-4">
                        {{ child.category_name }}
                    </td>
                    <td class="px-6 py-4">
                        {{ child.product_group_name }}
                    </td>
                    <td class="px-6 py-4 max-w-5">
                        <img :src="child.image_preview?.url" :alt="child.image_preview?.alt ?? null">
                    </td>
                    <td class="px-6 py-4 max-w-5">
                        <div class="flex gap-1">
                            <img v-for="image in child.product_gallery"
                                 :src="image?.url"
                                 :alt="image?.alt ?? null"
                                 class="w-8 h-8 object-contain">
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="prose prose-invert" v-html="child.content"></div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2 items-center">
                            <Link :href="route('admin.products.show', child.slug)">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                     stroke="currentColor" class="size-4 cursor-pointer text-emerald-600">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                </svg>
                            </Link>
                            <Link :href="route('admin.products.children.edit', {
                                product: item.slug,
                                productChild: child.slug
                            })">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                     stroke="currentColor" class="size-4 cursor-pointer text-emerald-600">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"/>
                                </svg>
                            </Link>
                            <svg @click="deleteProduct(child)" xmlns="http://www.w3.org/2000/svg" fill="none"
                                 viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="size-4 cursor-pointer text-red-600">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                            </svg>
                        </div>
                    </td>
                    <td></td>
                </tr>
            </template>

            </tbody>
        </table>
        <p class="text-gray-500 p-4" v-if="productsData?.data?.length === 0">Нет продуктов.</p>
    </div>


</template>

<script>
import {defineComponent} from 'vue'
import AdminLayout from "@/Layouts/AdminLayout.vue";
import {Link} from "@inertiajs/vue3";

export default defineComponent({
    name: "Index",
    layout: AdminLayout,

    props: {
        products: Object,
        trashedProducts: Object,
    },
    data() {
        return {
            productsData: {...this.products},
            trashedProductsData: {...this.trashedProducts},
        }
    },
    components: {
        Link,
    },
    methods: {
        deleteProduct(product) {
            axios.delete(route('admin.products.destroy', product.slug))
                .then(res => {
                    if(product.parent_id){
                        let parent =  this.productsData.data.find(item => item.id === product.parent_id )
                        parent.children.data = parent.children.data.filter(item => item.id !== product.id)
                        parent.children_count = parent.children_count - 1
                    }else{
                        this.productsData.data = this.productsData.data.filter(item => item.id !== product.id)
                        this.trashedProductsData.data.push(product);
                    }
                })
        },
        loadChildren(item) {
            if(!item.children){
                axios.get(route('admin.products.children', item.slug))
                    .then(res => {
                        item.children = res.data;
                        item.isOpened = true;
                    });
            }else {
                item.isOpened = true;
            }
        },
        closeChildren(item){
            if(item.isOpened) item.isOpened = false;
        }
    }
})
</script>

<style scoped>

</style>
