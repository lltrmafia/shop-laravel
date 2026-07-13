<script>
import {defineComponent} from 'vue'
import axios from "axios";

export default defineComponent({
    name: "Index",

    methods:{
        getProducts(){
            axios.get('/api/products')
                .then(res =>{
                    console.log(res.data);
                });
        }
    }
})
</script>

<template>
<h1 @click="getProducts">HOME</h1>
</template>

<style scoped>

</style>
