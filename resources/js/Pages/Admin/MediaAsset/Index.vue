<template>
    <div class="flex gap-4 items-center">
        <h1>Библиотека</h1>
        <a class="bg-gray-900 inline-block text-white px-4 py-3 rounded-lg" @click="openFileDialog">
            Добавить медиа
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

    <div class="flex gap-4 flex-wrap mt-10">
        <div class="max-w-40 justify-center overflow-hidden relative bg-gray-900 flex flex-col items-center text-white px-4 py-3 rounded-lg" v-for="image in imagesData">
            <img :src="image.image_url" alt="">
            <p class="absolute bottom-0 text-sm bg-white/10 w-full text-center p-1">{{image.filename}}</p>
        </div>
    </div>
</template>

<script>
import {defineComponent} from 'vue'
import AdminLayout from "@/Layouts/AdminLayout.vue";
import {Link} from "@inertiajs/vue3";
export default defineComponent({
    name: "Index",
    layout: AdminLayout,

    props:{
      images: Array
    },
    data(){
        return{
            imagesData: [...this.images]
        }
    },
    methods:{
        openFileDialog(){
            this.$refs.fileInput.click();
        },
        uploadMedia(event) {
            const fileList = event.target.files
            if (fileList.length === 0) return

            const formData = new FormData()
            for( let i = 0; i < fileList.length; i++){
                formData.append('images[]', fileList[i])
            }
            axios.post(route('admin.media.store'), formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
                .then(res => {
                    this.imagesData.unshift(...res.data);
                })
        }
    }
})
</script>

<style scoped>
h1{
    font-size: 40px;
    color: #fff;
    margin-bottom: 10px;
}
</style>
