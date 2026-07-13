<template>
    <ProductForm
        :entries="entries"
        :parentProduct="parentProduct"
        :categories="categories"
        :productGroups="productGroups"
        :params="params"
        :initialPreview="initialPreview"
        :initialGallery="initialGallery"
        :editorKey="editorKey"
        buttonText="Сохранить"
        @submit="updateProduct"
        v-model:success="success"
    />
</template>

<script>
import {defineComponent} from 'vue'
import AdminLayout from "@/Layouts/AdminLayout.vue";
import ProductForm from "@/Components/ProductForm.vue";

export default defineComponent({
    name: "EditChild",
    layout: AdminLayout,

    components: {
        ProductForm,
    },

    props: {
        parentProduct: Object,
        productChild: Object,
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
            success: false,
            entries: this.getInitialEntries(),
            initialPreview: this.productPreview,
            initialGallery: this.productGallery,
        }
    },
    methods: {
        updateProduct() {
            axios.patch(route('admin.products.update', this.entries.product.slug), this.entries)
                .then(res => {
                    this.success = true
                });
        },
        getInitialEntries() {
            return {
                product: this.productChild,
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
    watch: {
        'entries.product': {
            handler(new_val, old_val) {
                this.success = false
            },
            deep: true
        }
    }

})
</script>

