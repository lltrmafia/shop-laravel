<script>
import {defineComponent} from 'vue'
import {EditorContent, useEditor} from '@tiptap/vue-3'
import StarterKit from '@tiptap/starter-kit'
import {Placeholder} from "@tiptap/extension-placeholder";
import {TextStyle} from "@tiptap/extension-text-style";
import {Color} from "@tiptap/extension-text-style";

export default defineComponent({
    name: "TiptapEditor",

    components: {
        EditorContent,
    },

    props: {
        modelValue: {
            type: String,
            default: ''
        }
    },

    setup(props, {emit}) {

        const editor = useEditor({

            content: props.modelValue,

            extensions: [
                StarterKit,
                Placeholder.configure({
                    placeholder: 'Контент'
                }),
                TextStyle,
                Color,
            ],

            onUpdate: ({editor}) => {
                emit('update:modelValue', editor.getHTML())
            }
        })

        return {
            editor
        }
    }
})
</script>

<template>
    <div class="bg-[#101828] border border-white/20 text-white rounded-lg w-full min-h-80">
        <div v-if="editor" class="flex gap-2 mb-4 flex-wrap bg-white/10 p-4">

            <button
                type="button"
                @click="editor.chain().focus().toggleBold().run()"
                class="border px-3 py-1 rounded"
            >
                <b>Bold</b>
            </button>

            <button
                type="button"
                @click="editor.chain().focus().toggleItalic().run()"
                class="border px-3 py-1 rounded"
            >
                <i>Italic</i>
            </button>

            <button
                type="button"
                @click="editor.chain().focus().toggleBulletList().run()"
                class="border px-3 py-1 rounded"
            >
                UL
            </button>

            <button
                type="button"
                @click="editor.chain().focus().toggleOrderedList().run()"
                class="border px-3 py-1 rounded"
            >
                OL
            </button>

            <input
                type="color"
                @input="editor.chain().focus().setColor($event.target.value).run()"
                class="border px-1 py-1 rounded min-h-8 bg-transparent"
            />

            <button
                type="button"
                @click="editor.chain().focus().toggleHeading({ level: 1 }).run()"
                class="border px-3 py-1 rounded"
            >
                H1
            </button>

            <button
                type="button"
                @click="editor.chain().focus().toggleHeading({ level: 2 }).run()"
                class="border px-3 py-1 rounded"
            >
                H2
            </button>

            <button
                type="button"
                @click="editor.chain().focus().toggleHeading({ level: 3 }).run()"
                class="border px-3 py-1 rounded"
            >
                H3
            </button>

            <button
                type="button"
                @click="editor.chain().focus().toggleHeading({ level: 4 }).run()"
                class="border px-3 py-1 rounded"
            >
                H4
            </button>

            <button
                type="button"
                @click="editor.chain().focus().toggleHeading({ level: 5 }).run()"
                class="border px-3 py-1 rounded"
            >
                H5
            </button>

        </div>
        <div class="px-4 py-2">
            <EditorContent :editor="editor" aria-placeholder="Контент"/>
        </div>
    </div>
</template>

<style scoped>
:deep(.ProseMirror) {
    outline: none;
}

:deep(.ProseMirror p.is-editor-empty:first-child::before) {
    content: attr(data-placeholder);
    float: left;
    color: #999;
    pointer-events: none;
    height: 0;
}
</style>
