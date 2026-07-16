<template>
    <Link :href="route('admin.products.index')"
          class="border border-white/20 bg-gray-900 inline-block text-white px-4 py-3 rounded-lg">Назад
    </Link>
    <div v-if="success"
         class="border border-white/20 bg-emerald-900/60 w-full
         text-white px-4 py-3 rounded-b-lg absolute top-0 text-center left-1/2 -translate-x-1/2">
        Успешно сохранено!
    </div>
    <div class="w-full flex gap-2 mt-5 h-[calc(100vh-180px)] overflow-hidden">
        <div class="left flex-1 min-w-0 flex flex-col gap-2 overflow-y-auto py-5 px-2">
            <div>
                <div class="mb-4">
                    <input type="text" v-model="entries.product.title"
                           class="bg-[#101828] border border-white/20 text-white rounded-lg w-full"
                           placeholder="Заголовок">
                </div>
                <div class="mb-4">
                    <input type="text" v-model="entries.product.article"
                           class="bg-[#101828] border border-white/20 text-white rounded-lg w-full"
                           placeholder="Артикул">
                </div>
                <div class="mb-4">
                <textarea type="text" v-model="entries.product.description"
                          class="bg-[#101828] border border-white/20 text-white rounded-lg w-full"
                          placeholder="Описание"></textarea>
                </div>
                <div class="mb-4 w-full">
                    <TiptapEditor
                        :key="editorKey"
                        v-model="entries.product.content"/>
                </div>
                <div class="mb-4">
                    <input type="number" v-model="entries.product.price"
                           class="bg-[#101828] border border-white/20 text-white rounded-lg w-full"
                           placeholder="Цена">
                </div>
                <div class="mb-4">
                    <input type="number" v-model="entries.product.old_price"
                           class="bg-[#101828] border border-white/20 text-white rounded-lg w-full"
                           placeholder="Старая цена">
                </div>
                <div class="mb-4">
                    <input type="number" v-model="entries.product.qty"
                           class="bg-[#101828] border border-white/20 text-white rounded-lg w-full"
                           placeholder="Количество">
                </div>
                <div class="mb-4">
                    <select v-model="entries.product.category_id"
                            class="bg-[#101828] border border-white/20 text-white rounded-lg w-full">
                        <option :value="null" disabled>Выберите категорию</option>
                        <option v-for="category in categories.data" :value="category.id">{{ category.title }}</option>
                    </select>
                </div>
                <div class="mb-4">
                    <select v-model="entries.product.product_group_id"
                            class="bg-[#101828] border border-white/20 text-white rounded-lg w-full">
                        <option :value="null" disabled>Выберите родителя</option>
                        <option v-for="productGroup in productGroups.data" :value="productGroup.id">
                            {{ productGroup.title }}
                        </option>
                    </select>
                </div>
                <div class="mb-4">
                    <div class="flex gap-2">
                        <select v-model="paramOption.paramObj"
                                @change="disabledBtn=false"
                                class="bg-[#101828] border border-white/20 text-white">
                            <option :value="{}" disabled>Выберите характеристику</option>
                            <option :disabled="entries.params.some(item => item.id === param.id)"
                                    v-for="param in params.data" :value="param">
                                {{ param.title }}
                            </option>
                        </select>

                        <div class="flex gap-2">
                            <div class="relative" v-if="paramOption.paramObj?.input_type_name === 'color'">
                                <input
                                    v-model="paramOption.value"
                                    @focus="$refs.colorInput.click()"
                                    type="text"
                                    :disabled="disabledBtn"
                                    class="bg-[#101828] border border-white/20 text-white"
                                    placeholder="Значение">
                                <input
                                    ref="colorInput"
                                    type="color"
                                    :disabled="disabledBtn"
                                    value="#"
                                    v-model="paramOption.value"
                                    class="absolute inset-0 opacity-0 pointer-events-none">
                            </div>
                            <input v-else-if="paramOption.paramObj?.input_type_name === 'integer'"
                                   v-model="paramOption.value"
                                   type="number"
                                   :disabled="disabledBtn"
                                   class="bg-[#101828] border border-white/20 text-white"
                                   placeholder="Значение">
                            <input v-else
                                   v-model="paramOption.value"
                                   type="text"
                                   :disabled="disabledBtn"
                                   class="bg-[#101828] border border-white/20 text-white"
                                   placeholder="Значение">
                            <input v-if="paramOption.paramObj.is_variant"
                                   v-model="paramOption.display_name"
                                   type="text"
                                   placeholder="Название"
                                   :disabled="disabledBtn"
                                   class="bg-[#101828] border border-white/20 text-white">
                        </div>
                        <a @click.prevent="setParam"
                           class="bg-[#101828] border border-white/20 text-white px-4 flex items-center" href="#">+</a>
                    </div>
                </div>
                <div class="mb-4 flex flex-wrap gap-2">
                    <div
                        v-for="paramEntries in entries.params"
                        class="relative bg-[#101828] border border-white/20 text-white rounded-lg px-4 py-3">
                        <span
                            @click="deleteParam(paramEntries)"
                            class="absolute top-[-5px] right-[-5px] cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="red" class="size-4">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                            </svg>
                        </span>
                        <div>{{ paramEntries.title }} {{ paramEntries.value }}</div>
                    </div>
                </div>
            </div>
            <a @click.prevent="$emit('submit')"
               class="border border-white/20 bg-gray-900 inline-block text-white px-4 py-3 rounded-lg w-max"
               href="#">{{ buttonText }}</a>
        </div>
        <RightSidebar
            v-model:isMediaModalOpen="isMediaModalOpen"
            v-model:selectedPreview="selectedPreview"
            v-model:selectedGallery="selectedGallery"
            :productChildren="productChildren"
            :productChildrenTrashed="productChildrenTrashed"
            :parentProductId="parentProductId"
            :parentProduct="parentProduct"
            @modal-for-preview="modalForPreview"
            @modal-for-gallery="modalForGallery"
            @remove-gallery-item="removeGalleryItem"
            @remove-preview-item="removePreviewItem"
            @child-action="$emit('child-action', $event)"
        />
    </div>
    <MediaAssetPopup
        v-model:isMediaModalOpen="isMediaModalOpen"
        v-model:activeTab="activeTab"
        v-model:selectedGallery="selectedGallery"
        v-model:selectedPreview="selectedPreview"
        :tempModalMode="tempModalMode"
        @set-images="setImages"
    />

</template>

<script>
import {defineComponent} from 'vue'
import {Link} from "@inertiajs/vue3";
import TiptapEditor from "@/Components/TiptapEditor.vue";
import RightSidebar from "@/Components/RightSidebar.vue";
import MediaAssetPopup from "@/Components/MediaAssetPopup.vue";

export default defineComponent({
    name: "ProductForm",

    components: {
        MediaAssetPopup,
        RightSidebar,
        TiptapEditor,
        Link,
    },
    data() {
        return {
            isMediaModalOpen: false,
            activeTab: 'library',
            tempModalMode: null,
            paramOption: {
                paramObj: {},
                value: '',
                display_name: ''
            },
            selectedPreview: this.initialPreview,
            selectedGallery: [...this.initialGallery],
            disabledBtn: true,
            parentProductId: this.entries.product.slug
        }
    },
    props: {
        entries: Object,
        parentProduct: {
            type: Object,
            default: () => ({})
        },
        productChildren: {
            type: Array,
            default: () => []
        },
        productChildrenTrashed: {
            type: Array,
            default: () => []
        },
        categories: Object,
        productGroups: Object,
        params: Object,
        initialPreview: Object,
        initialGallery: Array,
        success: Boolean,
        editorKey: Number,
        buttonText: String,
        resetKey: {
            type: Number,
            default: 0
        }
    },
    computed: {
        mediaPreview() {
            return this.entries.media.mediaAssetPreview
        },
        mediaGallery() {
            return this.entries.media.mediaAssetGallery
        }
    },
    emits: [
        'update:success',
        'submit',
        'child-action'
    ],
    methods: {
        modalForPreview() {
            this.tempModalMode = 'preview'
            this.mediaPreview.current_media_mode = 'preview'
        },
        modalForGallery() {
            this.tempModalMode = 'gallery'
            this.mediaGallery.current_media_mode = 'gallery'
        },

        setImages(payload) {
            if (payload.mode === 'preview') {
                this.selectedPreview = payload.images ?? null
                this.mediaPreview.selected_image_id = this.selectedPreview.id
            }
            if (payload.mode === 'gallery') {
                this.selectedGallery = [
                    ...this.selectedGallery,
                    ...payload.images
                ]
                this.mediaGallery.selected_images_ids = this.selectedGallery.map(image => image.id)
                console.log(this.selectedGallery);
            }
            this.isMediaModalOpen = false
        },
        removePreviewItem() {
            this.selectedPreview = null;
            this.mediaPreview.selected_image_id = null;
        },
        removeGalleryItem(image) {
            this.selectedGallery = this.selectedGallery.filter(item => item.id !== image.id)
            this.mediaGallery.selected_images_ids =
                this.mediaGallery.selected_images_ids.filter(item => item !== image.id)
        },
        setParam() {
            if (this.paramOption.value.length === 0) return
            if (this.paramOption.paramObj.is_variant && this.paramOption.display_name.length === 0) return

            this.entries.params.push({
                id: this.paramOption.paramObj.id,
                title: this.paramOption.paramObj.title,
                value: String(this.paramOption.value),
                display_name: String(this.paramOption.display_name)
            })
            this.disabledBtn = true
            this.paramOption = {
                paramObj: {},
                value: '',
                display_name: ''
            };
        },
        deleteParam(param) {
            this.entries.params = this.entries.params.filter(item => item.id !== param.id)
        }
    },
    watch: {
        resetKey() {
            this.selectedPreview = null
            this.selectedGallery = []
        },
        'entries.product': {
            handler(new_val, old_val) {
                this.$emit('update:success', false)
            },
            deep: true
        }
    }
})
</script>

<style scoped>
:deep(.ProseMirror h1),
:deep(.ProseMirror h2), :deep(.ProseMirror h3),
:deep(.ProseMirror h4), :deep(.ProseMirror h5) {
    font-size: 30px;
    font-weight: bold;
    margin-bottom: 16px;
}

:deep(.ProseMirror h1) {
    font-size: 40px;
}

:deep(.ProseMirror h2) {
    font-size: 35px;
}

:deep(.ProseMirror h3) {
    font-size: 30px;
}

:deep(.ProseMirror h4) {
    font-size: 24px;
}

:deep(.ProseMirror h5) {
    font-size: 20px;
}

:deep(.ProseMirror p) {
    font-size: 16px;
    margin-bottom: 12px;
}

:deep(.ProseMirror ul) {
    margin-bottom: 12px;
    margin-left: 15px;
    list-style: disc;
    padding: 5px;
}

:deep(.ProseMirror ul li) {
    font-size: 16px;
    padding-bottom: 3px;
}

:deep(.ProseMirror ul li p) {
    margin: 0;
    padding: 0;
}
</style>
