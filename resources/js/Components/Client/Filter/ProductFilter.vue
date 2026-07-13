<template>
    <div class="mt-5">
        <div v-for="filterParam in params" :key="filterParam.id">
            <h4 class="my-2 text-md">{{ filterParam.title }}</h4>
            <div v-if="filterParam.input_type === 'color'"
                 class="my-2 flex flex-wrap gap-1">
                <label v-for="color in filterParam.values"
                       :key="color">
                    <input
                        v-model="filters[filterParam.id].value"
                        type="checkbox"
                        :value="color"
                        :style="{ backgroundColor: color }"
                        class="w-6 h-6 rounded-full border"/>
                </label>
            </div>
            <div v-else-if="filterParam.input_type === 'integer'"
                 class="mt-10">
                <Slider
                    v-model="filters[filterParam.id].value"
                    :min="filterParam.min"
                    :max="filterParam.max"
                    :tooltips="true"
                />

            </div>
            <div v-else>
                <label
                    v-for="value in filterParam.values"
                    class="flex items-center gap-1 my-1">
                    <input
                        v-model="filters[filterParam.id].value"
                        :value="value"
                        type="checkbox">
                    {{ value }}
                </label>
            </div>
        </div>
        <button @click="this.$emit('filter', this.filters)"
                class="mt-10 border border-white/20 bg-gray-900 block text-white px-4 py-3 rounded-lg w-full">
            Filter
        </button>
    </div>
</template>

<script>
import {defineComponent} from 'vue'
import Slider from '@vueform/slider'
import '@vueform/slider/themes/default.css'
export default defineComponent({
    name: "ProductFilter",

    components: {
        Slider
    },

    data(){
        return{
            filters: {},
        }
    },
    props: {
        params: Array
    },

    watch: {
      params:{
          immediate: true,
          handler(params){
              this.initFilters(params)
          }
      }
    },

    methods: {
        initFilters(params){
            this.filters = {}
            params.forEach(param => {
                if (param.input_type === 'integer') {

                    const values = param.values.map(v => Number(v));
                    param.min = Math.min(...values)
                    param.max = Math.max(...values);


                    this.filters[param.id] = {
                        input_type: 'integer',
                        value: [param.min === param.max ? param.min = 0 : param.min, param.max]
                    }
                } else {
                    this.filters[param.id] = {
                        input_type: param.input_type,
                        value: [],
                    }
                }
            })
        }
    }
})
</script>

<style>
.slider-target {
    width: 90%;
    margin: 0 auto;
}

.slider-connect, .slider-origin, .slider-tooltip {
    background: #111827 !important;
}

.slider-handle:focus {
    box-shadow: 0 0 3px 2px rgba(17, 24, 39, .4);
}

.slider-tooltip {
    border-color: #111827;
}

.slider-handle {
    background: #ccc;
}
</style>
