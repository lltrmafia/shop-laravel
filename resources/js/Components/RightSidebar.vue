<template>
    <div class="right w-1/4 shrink-0 bg-[#101828] border border-white/20 text-white rounded-sm overflow-y-auto p-5">
        <div class=" w-full min-h-96 flex flex-col gap-4">
            <div v-if="parentProduct.id" class="bg-[#101828] border border-white/20 text-white">
                <h4 class="border-b border-white/20 p-2">Родительский продукт</h4>
                <div class="p-2 flex flex-col gap-2">
                    <div class="flex gap-2 items-center justify-between">
                            <Link :href="route('admin.products.edit', parentProduct.slug)"
                                  class="flex flex-col items-center gap-2">
                            <img
                                class="w-32"
                                :src="parentProduct.image_preview.url" :alt="parentProduct.image_preview.alt">

                                {{ parentProduct.title }}
                            </Link>
                    </div>
                </div>
            </div>
            <div v-if="productChildren.length" class="bg-[#101828] border border-white/20 text-white">
                <h4 class="border-b border-white/20 p-2">Вариации</h4>
                <div class="p-2 flex flex-col gap-2">
                    <div v-for="child in productChildren"
                         class="group flex gap-2 items-center justify-between">
                        <div class="flex gap-2">
                            <img
                                class="w-7"
                                :src="child.image_preview.url" :alt="child.image_preview.alt">
                            <Link :href="route('admin.products.children.edit', {
                                    product: this.parentProductId,
                                    productChild: child.slug})">
                                {{ child.title }}
                            </Link>
                        </div>
                        <a @click.prevent="$emit('child-action', {
                                action: 'trash',
                                child
                            })"
                           class="opacity-0 group-hover:opacity-100 block text-[12px] text-red-600 italic hover:underline" href="#">
                            В корзину
                        </a>
                    </div>
                </div>
            </div>

            <div v-if="productChildrenTrashed.length" class="bg-[#101828] border border-white/20 text-white">
                <h4 class="border-b border-white/20 p-2">Корзина вариаций</h4>
                <div class="p-2 flex flex-col gap-2">
                    <div v-for="child in productChildrenTrashed"
                         class="group flex gap-2 items-center justify-between">
                        <div class="flex gap-2 opacity-50">
                            <img
                                class="w-7"
                                :src="child.image_preview.url" :alt="child.image_preview.alt">
                            <p>{{ child.title }}</p>
                        </div>
                        <div class="opacity-0 group-hover:opacity-100 flex flex-col">
                            <a @click.prevent="$emit('child-action', {
                                action: 'restore',
                                child
                            })"
                               class="text-[12px] text-emerald-600 italic hover:underline" href="#">
                                Восстановить
                            </a>

                            <a @click.prevent="$emit('child-action', {
                                action: 'force-delete',
                                child
                            })"
                               class="text-[12px] text-red-600 italic hover:underline" href="#">
                                Удалить навсегда
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-[#101828] border border-white/20 text-white">
                <h4 class="border-b border-white/20 p-2">Изображение товара</h4>
                <a v-if="!selectedPreview" @click="$emit('update:isMediaModalOpen', true); openPreviewModal()"
                   class="text-blue-300 underline text-sm py-4 block px-2" href="#">Задать изображение товара</a>
                <div v-if="selectedPreview" class="w-[100px] border border-white/20 m-2">
                    <img :src="selectedPreview.image_url" :alt="selectedPreview.alt">
                </div>
                <a v-if="selectedPreview" @click.prevent="$emit('remove-preview-item')"
                   class="text-red-600 underline text-sm py-4 block px-2" href="#">Удалить изображение товара</a>
            </div>
            <div class="bg-[#101828] border border-white/20 text-white">
                <h4 class="border-b border-white/20 p-2">Галерея товара</h4>
                <a @click="$emit('update:isMediaModalOpen', true); openGalleryModal()"
                   class="text-blue-300 underline text-sm py-4 block px-2" href="#">Добавить картинки галереи
                    товара</a>
                <div v-if="selectedGallery?.length" class="grid grid-cols-2 gap-2 m-2">
                    <div
                        v-for="image in selectedGallery"
                        class="group relative border border-white/20">
                        <img
                            :src="image.image_url" :alt="image.alt"
                            class="transition-opacity duration-200 group-hover:opacity-70">
                        <button
                            @click.stop="removeGalleryItem(image)"
                            type="button"
                            class="absolute top-2 text-[10px] right-2 w-4 h-4 rounded-full bg-red-600 text-white
                           opacity-0 group-hover:opacity-100 transition-opacity duration-200
                           flex items-center justify-center"
                        >
                            ✕
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {defineComponent} from 'vue'
import {Link} from "@inertiajs/vue3";

export default defineComponent({
    name: "RightSidebar",
    components: {Link},

    props: {
        productChildren: {
            type: Array,
            default: () => []
        },
        productChildrenTrashed: {
            type: Array,
            default: () => []
        },
        parentProduct: {
            type: Object,
            default: () => ({})
        },
        parentProductId: {
            type: String,
            default: null
        },
        isMediaModalOpen: Boolean,
        selectedPreview: {
            type: Object,
            default: () => ({})
        },
        selectedGallery: Array
    },
    emits: [
        'update:isMediaModalOpen',
        'update:selectedPreview',
        'modal-for-preview',
        'modal-for-gallery',
        'remove-gallery-item',
        'remove-preview-item',
        'child-action'
    ],
    mounted() {
        console.log(this.parentProduct);
    },
    methods: {
        openPreviewModal() {
            this.$emit('modal-for-preview')
        },
        openGalleryModal() {
            this.$emit('modal-for-gallery')
        },
        removeGalleryItem(image) {
            this.$emit('remove-gallery-item', image)
        }
    }
})
</script>

<style scoped>

</style>
