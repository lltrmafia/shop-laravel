<template>
    <Link :href="route('admin.params.index')" class="bg-gray-900 inline-block text-white px-4 py-3 rounded-lg">
        Назад
    </Link>
    <Link :href="route('admin.params.index')"
          class="inline-block text-gray-500 px-4 py-3 hover:underline">
        Характеристики ({{paramsData.length}})
    </Link>
    <div class="relative overflow-x-auto mt-4 rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-white">
            <thead class="text-sm text-body bg-white/20 border-b border-white/20">
            <tr>
                <th scope="col" class="px-6 py-3 font-semibold">
                    Param name
                </th>
                <th scope="col" class="px-6 py-3 font-semibold">
                    Param id
                </th>
                <th scope="col" class="px-6 py-3 font-semibold">
                    Filter Type
                </th>
                <th scope="col" class="px-6 py-3 font-semibold">
                    Action
                </th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="item in trashedParamsData" class="text-sm text-body bg-[#101828] border-b border-white/20">
                <td class="px-6 py-4">
                    {{ item.title }}
                </td>
                <td class="px-6 py-4">
                    {{ item.id }}
                </td>
                <td class="px-6 py-4">
                    {{ item.filter_type_name }}
                </td>
                <td class="px-6 py-4">
                    <div class="flex gap-1 items-start flex-col">
                        <a @click.prevent="deleteForever(item)" class="text-sm text-red-600" href="#">Удалить навсегда</a>
                        <a @click.prevent="restoreParam(item)" class="text-sm text-green-600" href="#">Восстановить</a>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
        <p class="text-gray-500 p-4" v-if="trashedParamsData.length === 0">Корзина пуста.</p>
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
        trashedParams: Array,
        params: Array
    },
    data(){
        return{
            trashedParamsData: [...this.trashedParams],
            paramsData: [...this.params]
        }
    },
    components: {
        Link,
    },
    methods: {
        deleteForever(param){
            const deletedId = param.id
            axios.delete(route('admin.params.force-delete', param.id))
                .then(res=>{
                    this.trashedParamsData = this.trashedParamsData.filter(item => item.id !== deletedId)
                })
        },
        restoreParam(param){
            axios.post(route('admin.params.restore', param.id))
                .then(res=> {
                    this.trashedParamsData = this.trashedParamsData.filter(item => item.id !== param.id)
                    this.paramsData.push(param);
                })
        }
    }
})
</script>

<style scoped>

</style>
