<template>
    <div class="flex w-full gap-4">

<!--         Миниатюры-->
        <Swiper
            class="w-28 bg-gray-50"
            direction="vertical"
            :space-between="10"
            :slides-per-view="5"
            :modules="swiperModules"
            watch-slides-progress
            @swiper="thumbsSwiper = $event"
        >
            <SwiperSlide
                v-for="image in images"
                :key="image.url"
                class="max-h-[100px]"
            >
                <img
                    :src="image.url"
                    :alt="image.alt"
                    class="cursor-pointer rounded-md"
                >
            </SwiperSlide>
        </Swiper>

        <!-- Большое фото -->
        <Swiper
            class="w-full bg-gray-50"
            :modules="swiperModules"
            :thumbs="{ swiper: thumbsSwiper }"
            navigation
        >
            <SwiperSlide
                v-for="image in images"
                :key="image.url"
            >
                <img
                    :src="image.url"
                    :alt="image.alt"
                    class=""
                >
            </SwiperSlide>
        </Swiper>

    </div>
</template>

<script>
import {defineComponent} from 'vue'
import {Swiper, SwiperSlide} from "swiper/vue";
import {Navigation, Thumbs} from "swiper/modules";

import 'swiper/css'
import 'swiper/css/navigation'
import 'swiper/css/thumbs'

export default defineComponent({
    name: "ProductGallery",

    components: {
        Swiper,
        SwiperSlide,
    },

    data(){
        return {
            thumbsSwiper: null,
            swiperModules: [Navigation, Thumbs]
        }
    },

    props: {
        images: Array
    }
})
</script>

<style scoped>
.swiper-slide-thumb-active{
    border: 1px solid #1D4ED8;
    border-radius: 8px;

}
.swiper-slide{
    display: flex;
    align-items: center;
    justify-content: center;
}
.swiper-watch-progress .swiper-slide{
    padding: 10px;
}
</style>
