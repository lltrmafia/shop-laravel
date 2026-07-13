<template>
    <div v-if="isMediaModalOpen"
         @click="$emit('update:isMediaModalOpen')"
         class="modal-overlay fixed inset-0 bg-black/70 flex items-center justify-center z-50">
        <div
            @click.stop
            class="modal-window bg-[#101828] w-[95%] h-[90%] relative text-white overflow-hidden flex flex-col">
            <div class="modal-header px-6 pt-6 relative z-10 flex">
                <div class="flex gap-4">
                    <button
                        @click="$emit('update:activeTab', 'upload')"
                        :class="[
                            'px-4 py-2transition py-2 mb-[-1px] border border-b-0',
                            activeTab === 'upload'
                                ? 'border border-white/20 border-b-0 bg-[#101828]'
                                : 'border-transparent'
                        ]">
                        Загрузить файлы
                    </button>
                    <button
                        @click="$emit('update:activeTab', 'library')"
                        :class="[
                            'px-4 py-2transition py-2 mb-[-1px] border border-b-0',
                            activeTab === 'library'
                                ? 'border border-white/20 border-b-0 bg-[#101828]'
                                : 'border-transparent'
                        ]">
                        Библиотека файлов
                    </button>
                </div>
                <button @click="$emit('update:isMediaModalOpen'); resetModalState()"
                        class="absolute top-4 right-6 text-white">
                    ✕
                </button>
            </div>
            <div class="modal-content p-6 border-t border-white/20 flex-1 overflow-hidden pt-4">
                <div v-if="activeTab === 'library'" class="flex justify-between h-full">
                    <div class="flex gap-4 flex-wrap items-start overflow-y-auto min-h-0 pb-20 pt-6 pr-6">
                        <div v-if="mediaData.length"
                             @click="toggleTempSelection(image)"
                             v-for="image in mediaData"
                             :class="[
                                 'cursor-pointer w-40 justify-center relative bg-gray-900 flex flex-col items-center text-white px-4 py-3',
                                 tempSelection.includes(image.id)
                                    ? 'border-[3px] border-sky-700 123'
                                    : '',
                                    isInGallery(image)
                                    ? 'border-[3px] border-sky-700 pointer-events-none'
                                    : ''
                                 ]">
                            <div class="h-32 flex items-center justify-center p-2 relative">
                                <div v-if="isInGallery(image)"
                                     class="absolute z-10">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5" class="size-10">
                                        <path class="stroke-sky-700" stroke-linecap="round" stroke-linejoin="round"
                                              d="m4.5 12.75 6 6 9-13.5"/>
                                    </svg>
                                </div>
                                <img :src="image.image_url" alt="" :class="[
                                    'max-h-full object-contain',
                                    isInGallery(image)
                                    ? 'opacity-50 pointer-events-none'
                                    : ''
                                    ]">
                            </div>
                            <p class="absolute bottom-0 text-sm bg-white/10 w-full text-center p-1">
                                {{ image.filename }}</p>
                            <div v-if="tempSelection.includes(image.id)"
                                 class="w-[23px] h-[23px] bg-white absolute top-[-10px] right-[-10px] p-[1px]">
                                <span class="bg-sky-900 flex justify-center items-center h-full w-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5" stroke="currentColor" class="size-4">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <div v-else>Библиотека пуста.</div>
                    </div>
                    <MediaAssetInfo
                        :imageData="imageData"
                        :isSaving="isSaving"
                        v-model:isEdited="isEdited"
                        v-model:isSaved="isSaved"
                        @update-image="updateImage"
                        @delete-image="deleteImage"
                    />
                </div>

                <div v-if="activeTab === 'upload'" class="h-full">
                    <div class="flex gap-4 items-center justify-center flex-col h-full">
                        <h1 class="text-lg">Перетащите файлы сюда</h1>
                        <span>или</span>
                        <a class="px-4 py-4 bg-sky-800 text-white rounded-lg text-lg min-w-[250px] text-center cursor-pointer"
                           @click="openFileDialog">
                            Выберите файлы
                        </a>
                        <input
                            ref="fileInput"
                            type="file"
                            accept="image/*"
                            class="hidden"
                            multiple
                            @change="uploadMedia"
                        >
                    </div>
                </div>
            </div>
            <div class="modal-bottom p-6 flex justify-end">
                <button
                    @click="sendMediaToParent();"
                    type="button"
                    class="px-4 py-2 bg-sky-800 disabled:bg-gray-400 disabled:text-gray-700 disabled:cursor-not-allowed"
                    :disabled="!tempSelection.length">Задать изображение товара
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import {defineComponent} from 'vue'
import MediaAssetInfo from "@/Components/MediaAssetInfo.vue";

export default defineComponent({
    name: "MediaAssetPopup",
    components: {MediaAssetInfo},

    props: {
        isMediaModalOpen: Boolean,
        activeTab: String,
        selectedGallery: Array,
        tempModalMode: String,
    },
    data() {
        return {
            mediaData: [],
            tempSelection: [],
            imageData: null,
            isSaving: false,
            isEdited: false,
            isSaved: false,
        }
    },
    emits: [
        'update:isMediaModalOpen',
        'update:activeTab',
        'update:selectedGallery',
        'set-images',
        'reset-modal-state'
    ],
    watch: {
        isMediaModalOpen(value) {
            if (value && this.mediaData.length === 0) this.loadMedia()
            if (!value){
                this.tempSelection = []
                this.imageData = null
            }
        },
    },

    methods: {
        updateImage() {
            this.isSaving = true
            axios.patch(route('admin.media.update', this.imageData.id), {
                alt: this.imageData.alt
            })
                .then(res => {
                    this.isEdited = false
                    this.isSaved = true
                })
                .finally(() => {
                    this.isSaving = false
                })
        },
        deleteImage(image) {
            let message = `Изображение привязано к (${image.pivot_products_count}) сущностям. Удалить везде?`
            if (!confirm(message)) {
                return
            }
            axios.delete(route('admin.media.destroy', image.id))
                .then(res => {
                    this.mediaData = this.mediaData.filter(item => item.id !== image.id)
                    this.imageData = null
                })
        },
        toggleTempSelection(image) {
            if (this.tempModalMode === 'preview') {
                this.tempSelection = [image.id]
            }
            if (this.tempModalMode === 'gallery') {
                this.tempSelection.includes(image.id) ? this.tempSelection = this.tempSelection.filter(id => id !== image.id)
                    : this.tempSelection.push(image.id)
            }
            this.imageData = this.mediaData.find(item => item.id === image.id)
            this.isSaved = false

        },
        sendMediaToParent(){
            if (this.tempModalMode === 'preview') {
                this.tempSelection = this.mediaData.find(
                    item => item.id === this.tempSelection[0])
            }
            if (this.tempModalMode === 'gallery') {
                this.tempSelection = this.mediaData.filter(
                    item => this.tempSelection.includes(item.id))
            }
            this.$emit('set-images', {
                mode: this.tempModalMode,
                images: this.tempSelection
            })
        },
        resetModalState() {
            this.tempSelection = []
        },
        loadMedia() {
            axios.get(route('admin.media.list'))
                .then(res => {
                    this.mediaData = res.data
                })
        },
        isInGallery(image) {
            return this.tempModalMode === 'gallery'
                && this.selectedGallery.some(item => item.id === image.id)
        },
        openFileDialog() {
            this.$refs.fileInput.click();
        },
        uploadMedia(event) {
            const fileList = event.target.files
            if (fileList.length === 0) return

            const formData = new FormData()
            for (let i = 0; i < fileList.length; i++) {
                formData.append('images[]', fileList[i])
            }
            axios.post(route('admin.media.store'), formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
                .then(res => {
                    this.mediaData.unshift(...res.data);
                    this.imageData = res.data[0];
                    this.tempSelection = res.data.map(image => image.id)
                    this.$emit('update:activeTab', 'library')
                })
        }
    }
})
</script>

<style scoped>

</style>
