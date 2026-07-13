<template>
    <div class="min-w-[300px] overflow-y-auto min-h-0">
        <div v-if="imageData" class="p-4 flex flex-col gap-2">
            <h3 class="uppercase mb-4">Детали вложения</h3>
            <img class="w-16" :src="imageData.image_url" alt=""/>
            <div class="flex flex-col">
                <span class="text-[#ccc] text-sm">{{ imageData.size_human }}</span>
                <span class="text-[#ccc] text-sm">{{ imageData.width }} на {{ imageData.height }} пикселей</span>
                <a @click.prevent="deleteImage(imageData)" href="#" class="text-sm text-red-600">Удалить навсегда</a>
            </div>
            <div>
                <label class="text-sm text-[#ccc]">Атрибут alt</label>
                <input type="text" v-model="imageData.alt"
                       class="bg-[#101828] border border-white/20 text-white rounded-lg w-full text-sm mt-2"
                       placeholder="Опишите назначение изображения"
                       @input=" $emit('update:isEdited', true); $emit('update:isSaved', false)">
            </div>
            <button @click.prevent="updateImage"
                    :disabled="isSaving || !isEdited"
                    type="button"
                    class="px-4 py-2 bg-sky-800 disabled:bg-gray-400 disabled:text-gray-700 disabled:cursor-not-allowed">
                <span v-if="isSaving" class="inline-block w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
                <span v-else-if="isSaved">Сохранено</span>
                <span v-else>Сохранить</span>

            </button>
        </div>
    </div>
</template>

<script>
import {defineComponent} from 'vue'

export default defineComponent({
    name: "MediaAssetInfo",

    props: {
        imageData: {
            type: Object,
            default: null,
        },
        isSaving: Boolean,
        isEdited: Boolean,
        isSaved: Boolean
    },
    emits: [
        'update:isEdited',
        'update:isSaved',
        'update-image',
        'delete-image',
    ],
    methods:{
      updateImage(){
        this.$emit('update-image')
      },
      deleteImage(image){
        this.$emit('delete-image', image)
      }
    },
})
</script>

<style scoped>

</style>
