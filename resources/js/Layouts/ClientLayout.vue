<template>
    <Header />

    <main class="flex flex-col px-10">
        <router-view />
    </main>

    <Footer />
</template>

<script>
import Header from "@/Components/Client/Header.vue";
import Footer from "@/Components/Client/Footer.vue";
export default {
    name: "ClientLayout",

    components: {
        Header,
        Footer,
    }
}

</script>
