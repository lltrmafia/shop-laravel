<template>
    <Link :href="route('admin.product-groups.index')"
          class="border border-white/20 bg-gray-900 inline-block text-white px-4 py-3 rounded-lg">Назад
    </Link>

    <div class="w-1/4 flex flex-col gap-2 mt-5">
        <input class="bg-[#101828] border border-white/20 text-white rounded-lg" v-model="productGroup.title" type="text"
               placeholder="Заголовок">
        <a @click.prevent="storeProductGroup" class="border border-white/20 bg-gray-900 inline-block text-white px-4 py-3 rounded-lg w-max" href="#">Создать</a>
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
    data(){
        return{
            productGroup: {
                title: '',
            }
        }
    },

    methods: {
        storeProductGroup(){
            axios.post(route('admin.product-groups.store'), this.productGroup)
                .then(res=>{
                    this.productGroup.title = null;
                })
        }
    }
})
</script>

<style scoped>

</style>
