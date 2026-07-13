<template>
    <Link :href="route('admin.params.index')" class="border border-white/20 bg-gray-900 inline-block text-white px-4 py-3 rounded-lg">Назад</Link>
    <div v-if="success"
         class="border border-white/20 bg-emerald-900/60 w-full
         text-white px-4 py-3 rounded-b-lg absolute top-0 text-center left-1/2 -translate-x-1/2">
        Успешно сохранено!
    </div>
    <div class="w-1/4 flex flex-col gap-2 mt-5">
        <input class="bg-[#101828] border border-white/20 text-white rounded-lg" v-model="param.title" type="text" placeholder="Заголовок">
        <select class="bg-[#101828] border border-white/20 text-white rounded-lg" v-model="param.filter_type">
            <option :value="null" disabled>Выберите тип фильтра</option>
            <option v-for="filterType in filterTypes" :value="filterType.value">{{ filterType.label_cyrillic }}</option>
        </select>
        <a @click.prevent="updateCategory" class="border border-white/20 bg-gray-900 inline-block text-white px-4 py-3 rounded-lg w-max"
           href="#">Обновить</a>
    </div>


</template>

<script>
import {defineComponent} from 'vue'
import AdminLayout from "@/Layouts/AdminLayout.vue";
import {Link} from "@inertiajs/vue3";

export default defineComponent({
    name: "Index",
    layout: AdminLayout,

    components: {
        Link,
    },

    props: {
        param: Object,
        filterTypes: Array
    },

    data() {
        return {
            success: false,
        }
    },

    methods: {
        updateCategory() {
            axios.patch(route('admin.params.update', this.param.id), this.param)
                .then(res => {
                    this.success = true
                });
        }
    },
    watch: {
        param: {
            handler(new_val, old_val){
                this.success = false
            },
            deep: true
        }
    },
})
</script>

<style scoped>

</style>
