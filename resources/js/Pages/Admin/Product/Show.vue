<template>
    <Link :href="route('admin.products.index')" class="bg-gray-900 inline-block text-white px-4 py-3 rounded-lg">
        Назад
    </Link>

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
            </tr>
            </thead>
            <tbody>
            <tr class="text-sm text-body bg-[#101828] border-b border-white/20">
                <td class="px-6 py-4">
                    {{ product.data.title }}
                </td>
                <td class="px-6 py-4">
                    {{ product.data.id }}
                </td>
                <td class="px-6 py-4">
                    {{ product.data.slug }}
                </td>
                <td class="px-6 py-4">
                    {{ product.data.category_name }}
                </td>
                <td class="px-6 py-4">
                    {{ product.data.product_group_name }}
                </td>
                <td class="px-6 py-4">
                    {{ product.data.qty }}
                </td>
                <td class="px-6 py-4">
                    {{ product.data.description }}
                </td>
            </tr>

            </tbody>
        </table>
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
        product: Object,
    },

    components: {
        Link,
    }
})
</script>

<style scoped>

</style>
