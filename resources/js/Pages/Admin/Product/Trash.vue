<template>
    <div>
        <Link :href="route('admin.products.index')" class="bg-gray-900 inline-block text-white px-4 py-3 rounded-lg">
            Назад
        </Link>
        <Link :href="route('admin.products.index')"
              class="inline-block text-gray-500 px-4 py-3 hover:underline">
            Товары ({{ products.data.length }})
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
                    Количество
                </th>
                <th scope="col" class="px-6 py-3 font-semibold">
                    Описание
                </th>
                <th scope="col" class="px-6 py-3 font-semibold">
                    Действие
                </th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="item in trashedProductsData.data" class="text-sm text-body bg-[#101828] border-b border-white/20">
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
                <td class="px-6 py-4">
                    {{ item.qty }}
                </td>
                <td class="px-6 py-4">
                    {{ item.description }}
                </td>
                <td class="px-6 py-4">
                    <div class="flex gap-1 items-start flex-col">
                        <a @click.prevent="deleteForever(item)" class="text-sm text-red-600" href="#">Удалить навсегда</a>
                        <a @click.prevent="restoreProduct(item)" class="text-sm text-green-600" href="#">Восстановить</a>
                    </div>
                </td>
            </tr>

            </tbody>
        </table>
        <p class="text-gray-500 p-4" v-if="trashedProductsData?.data?.length === 0">Нет продуктов.</p>
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
            trashedProductsData: {...this.trashedProducts},
            productsData: {...this.products},
        }
    },
    components: {
        Link,
    },
    methods: {
        deleteForever(product) {
            const deletedId = product.id
            axios.delete(route('admin.products.force-delete', deletedId))
                .then(res => {
                    this.trashedProductsData.data = this.trashedProductsData.data.filter(item => item.id !== deletedId)
                })
        },
        restoreProduct(product){
            const deletedId = product.id
            axios.post(route('admin.products.restore', deletedId))
                .then(res=> {
                    this.trashedProductsData.data = this.trashedProductsData.data.filter(item => item.id !== deletedId)
                    if(!product.parent_id) this.productsData.data.push(product);
                })

        }
    }
})
</script>

<style scoped>

</style>
