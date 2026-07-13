<template>
    <ProductForm
        :entries="entries"
        :categories="categories"
        :params="params"
        :productGroups="productGroups"
        :initialPreview="initialPreview"
        :initialGallery="initialGallery"
        :editorKey="editorKey"
        :reset-key="resetKey"
        buttonText="Создать"
        @submit="storeProduct"
    />
</template>
<script>
import {defineComponent} from 'vue'
import AdminLayout from "@/Layouts/AdminLayout.vue";
import ProductForm from "@/Components/ProductForm.vue";

export default defineComponent({
    name: "Create",
    layout: AdminLayout,

    components: {
        ProductForm
    },
    props: {
        categories: Object,
        productGroups: Object,
        params: Object
    },
    data() {
        return {
            editorKey: 0,
            entries: this.getInitialEntries(),
            initialPreview: null,
            initialGallery: [],
            resetKey: 0,
        }
    },

    methods: {
        storeProduct() {
            axios.post(route('admin.products.store'), this.entries)
                .then(res => {
                    this.entries = this.getInitialEntries()
                    this.editorKey++
                    this.resetKey++
                })
        },

        getInitialEntries() {
            return {
                product: {
                    category_id: null,
                    product_group_id: null,
                },
                params: [],
                media: {
                    mediaAssetPreview: {
                        selected_image_id: null,
                        current_media_mode: '',
                        sort_order: 0
                    },
                    mediaAssetGallery: {
                        selected_images_ids: [],
                        current_media_mode: '',
                        sort_order: 0
                    }
                },
            }
        }
    }
})
</script>
