<template>
    <ProductForm
        :entries="entries"
        :productChildren="productChildrenData"
        :productChildrenTrashed="productChildrenTrashedData"
        :categories="categories"
        :productGroups="productGroups"
        :params="params"
        :initialPreview="initialPreview"
        :initialGallery="initialGallery"
        :editorKey="editorKey"
        buttonText="Сохранить"
        @submit="updateProduct"
        v-model:success="success"
        @child-action="childAction"
    />
</template>

<script>
import {defineComponent} from 'vue'
import AdminLayout from "@/Layouts/AdminLayout.vue";
import ProductForm from "@/Components/ProductForm.vue";

export default defineComponent({
    name: "Edit",
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
        productChildren: Array,
        productChildrenTrashed: Array,

    },

    data() {
        return {
            editorKey: 0,
            success: false,
            entries: this.getInitialEntries(),
            productChildrenData: [...this.productChildren],
            productChildrenTrashedData: [...this.productChildrenTrashed],
            initialPreview: this.productPreview,
            initialGallery: this.productGallery,
        }
    },
    methods: {
        updateProduct() {
            axios.patch(route('admin.products.update', this.product.slug), this.entries)
                .then(res => {
                    this.success = true
                });
        },
        childAction({action, child}) {
            switch (action) {
                case 'trash':
                    this.deleteChild(child)
                    break;

                case 'restore':
                    this.restoreChild(child)
                    break;

                case 'force-delete':
                    this.forceDeleteChild(child)
                    break;
            }
        },
        deleteChild(child) {
            axios.delete(route('admin.products.destroy', child.slug))
                .then(res => {
                    this.productChildrenData = this.productChildrenData.filter(item => item.id !== child.id)
                    this.productChildrenTrashedData.push(child)
                })
        },
        restoreChild(child) {
            axios.post(route('admin.products.restore', child.id))
                .then(res=> {
                    this.productChildrenTrashedData = this.productChildrenTrashedData.filter(item => item.id !== child.id)
                    this.productChildrenData.push(child)
                })
        },
        forceDeleteChild(child) {
            axios.delete(route('admin.products.force-delete', child.id))
                .then(res => {
                    this.productChildrenTrashedData = this.productChildrenTrashedData.filter(item => item.id !== child.id)
                })
        },
        getInitialEntries() {
            return {
                product: this.product,

                params: this.paramsProduct,

                media: {
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

