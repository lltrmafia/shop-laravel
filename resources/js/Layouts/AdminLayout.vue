<template>
    <section class="w-full p-4 border-b bg-gray-900 text-white fixed top-0 border-[#1e2939]">
        <header>
            Market Admin
        </header>
    </section>
    <section id="layout-fixed" class="flex fixed top-[57px] bottom-0 left-0 right-0 overflow-hidden">
        <aside class="w-1/5 bg-[#030712] border border-[#1e2939] border-t-0 overflow-y-auto">
            <nav>
                <Link :href="route('dashboard')" class="block p-4 text-white">Панель</Link>
                <Link :href="route('admin.product-groups.index')" class="block p-4 text-white">Группа продуктов</Link>
                <Link :href="route('admin.products.index')" class="block p-4 text-white">Продукты</Link>
                <Link :href="route('admin.categories.index')" class="block p-4 text-white">Категории</Link>
                <Link :href="route('admin.params.index')" class="block p-4 text-white">Характеристики</Link>
                <Link :href="route('admin.media.index')" class="block p-4 text-white">Медиафайлы</Link>
            </nav>
        </aside>
        <article class="p-4 block flex-1 bg-[#030712]">
            <slot />
        </article>
    </section>
    <section>
        <footer>

        </footer>
    </section>
</template>

<script>
import {Link} from "@inertiajs/vue3";

export default {
    name: "AdminLayout",

    components: {
        Link
    }
}
</script>

<style>
::-webkit-scrollbar {
    width: 10px;
}

::-webkit-scrollbar-track {
    background: transparent;
}

::-webkit-scrollbar-thumb {
    background: #334155;
    border-radius: 999px;
    border: 2px solid transparent;
    background-clip: content-box;
}

::-webkit-scrollbar-thumb:hover {
    background: #475569;
    background-clip: content-box;
}

</style>
