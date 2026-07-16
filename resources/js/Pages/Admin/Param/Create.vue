<template>
    <Link :href="route('admin.params.index')"
          class="border border-white/20 bg-gray-900 inline-block text-white px-4 py-3 rounded-lg">Назад
    </Link>

    <div class="w-1/4 flex flex-col gap-2 mt-5">
        <input class="bg-[#101828] border border-white/20 text-white rounded-lg" v-model="param.title" type="text"
               placeholder="Заголовок">
        <select class="bg-[#101828] border border-white/20 text-white rounded-lg" v-model="param.filter_type">
            <option :value="null" disabled>Выберите тип фильтра</option>
            <option v-for="filterType in filterTypes" :value="filterType.value">{{ filterType.label_cyrillic }}</option>
        </select>
        <select class="bg-[#101828] border border-white/20 text-white rounded-lg" v-model="param.input_type">
            <option :value="null" disabled>Выберите тип характеристики</option>
            <option v-for="inputType in inputTypes" :value="inputType.value">{{ inputType.title }}</option>
        </select>
        <label for="variant"
               class="flex items-center gap-2 bg-[#101828] border border-white/20 text-white rounded-lg p-3">
            Вариативная характеристика?
            <input v-model="param.is_variant"
                   id="variant" type="checkbox" class="cursor-pointer">
        </label>
        <a @click.prevent="storeParam"
           class="border border-white/20 bg-gray-900 inline-block text-white px-4 py-3 rounded-lg w-max" href="#">Создать</a>
    </div>


</template>

<script>
import {defineComponent} from 'vue'
import AdminLayout from "@/Layouts/AdminLayout.vue";
import {Link} from "@inertiajs/vue3";
import Checkbox from "@/Components/Checkbox.vue";

export default defineComponent({
    name: "Index",
    layout: AdminLayout,

    components: {
        Checkbox,
        Link,
    },

    props: {
        filterTypes: Array,
        inputTypes: Array
    },
    data() {
        return {
            param: {
                filter_type: null,
                input_type: null,
                title: '',
                is_variant: false,
            }
        }
    },

    methods: {
        storeParam() {
            axios.post(route('admin.params.store'), this.param)
                .then(res => {
                    this.param = {
                        filter_type: null,
                        input_type: null,
                        is_variant: false,
                    }
                })
        }
    }
})
</script>

<style scoped>

</style>
