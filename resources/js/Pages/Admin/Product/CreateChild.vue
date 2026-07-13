<template>
    <ProductForm
        :entries="entries"
        :categories="categories"
        :productGroups="productGroups"
        :params="params"
        :initialPreview="initialPreview"
        :initialGallery="initialGallery"
        :editorKey="editorKey"
        buttonText="Создать"
        @submit="storeProduct"
    />
</template>

<script>
import {defineComponent} from 'vue'
import AdminLayout from "@/Layouts/AdminLayout.vue";
import ProductForm from "@/Components/ProductForm.vue";

export default defineComponent({
    name: "CreateChild",
    layout: AdminLayout,

    components: {
        ProductForm,
    },

    props: {
        product: Object,
        categories: Object,
        productGroups: Object,
        productPreview: Object,
        productGallery: Array,
        params: Object,
        paramsProduct: Array,
    },

    data() {
        return {
            editorKey: 0,
            //success: false,
            entries: this.getInitialEntries(),
            initialPreview: this.productPreview,
            initialGallery: this.productGallery,
        }
    },
    methods: {
        storeProduct() {
            this.entries.product.parent_id = this.product.id
            axios.post(route('admin.products.store'), this.entries)
                .then(res => {
                    //this.entries = this.getInitialEntries()
                    //this.editorKey++
                    // this.resetKey++
                })
        },
        getInitialEntries() {
            return {
                product: this.product,

                params: this.paramsProduct,

                media:{
                    mediaAssetPreview: {
                        selected_image_id: this.productPreview?.id ?? null,
                        current_media_mode: 'preview',
                        sort_order: 0
                    },
                    mediaAssetGallery: {
                        selected_images_ids: this.productGallery?.map(image => image.id) ?? [],
                        current_media_mode: 'gallery',
                        sort_order: 0
                    }
                }
            }
        }
    },
    // watch: {
    //     'entries.product': {
    //         handler(new_val, old_val) {
    //             this.success = false
    //         },
    //         deep: true
    //     }
    // }

})
</script>

