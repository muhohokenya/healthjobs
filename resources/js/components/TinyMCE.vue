<template>
    <div>
        <textarea :id="editorId" v-model="content"></textarea>
    </div>
</template>

<script setup lang="ts">
import { ref, watch, onMounted, onBeforeUnmount } from 'vue';
import tinymce from 'tinymce/tinymce';

// Import themes
import 'tinymce/themes/silver';

// Import plugins
import 'tinymce/plugins/advlist';
import 'tinymce/plugins/autolink';
import 'tinymce/plugins/lists';
import 'tinymce/plugins/link';
import 'tinymce/plugins/image';
import 'tinymce/plugins/charmap';
import 'tinymce/plugins/anchor';
import 'tinymce/plugins/searchreplace';
import 'tinymce/plugins/visualblocks';
import 'tinymce/plugins/code';
import 'tinymce/plugins/fullscreen';
import 'tinymce/plugins/insertdatetime';
import 'tinymce/plugins/media';
import 'tinymce/plugins/table';
import 'tinymce/plugins/help';
import 'tinymce/plugins/wordcount';

// Import content CSS
import 'tinymce/skins/ui/oxide/skin.css';

interface Props {
    modelValue: string;
    config?: Record<string, any>;
    disabled?: boolean;
}

interface Emits {
    (e: 'update:modelValue', value: string): void;
}

const props = withDefaults(defineProps<Props>(), {
    modelValue: '',
    config: () => ({}),
    disabled: false
});

const emit = defineEmits<Emits>();

const editor = ref<any>(null);
const editorId = ref(`tinymce-${Math.random().toString(36).substr(2, 9)}`);
const content = ref(props.modelValue);

watch(() => props.modelValue, (newValue) => {
    if (editor.value && newValue !== editor.value.getContent()) {
        editor.value.setContent(newValue || '');
    }
});

watch(() => props.disabled, (newValue) => {
    if (editor.value) {
        editor.value.setMode(newValue ? 'readonly' : 'design');
    }
});

const initEditor = () => {
    const defaultConfig = {
        selector: `#${editorId.value}`,
        height: 300,
        menubar: false,
        plugins: [
            'advlist', 'autolink', 'lists', 'link', 'image', 'charmap',
            'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
            'insertdatetime', 'media', 'table', 'help', 'wordcount'
        ],
        toolbar: 'undo redo | blocks | ' +
            'bold italic forecolor | alignleft aligncenter ' +
            'alignright alignjustify | bullist numlist outdent indent | ' +
            'removeformat | help',
        content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }',
        skin: false,
        content_css: false,
        setup: (editorInstance: any) => {
            editorInstance.on('init', () => {
                editor.value = editorInstance;
                editorInstance.setContent(props.modelValue || '');
                if (props.disabled) {
                    editorInstance.setMode('readonly');
                }
            });

            editorInstance.on('input change undo redo', () => {
                const newContent = editorInstance.getContent();
                emit('update:modelValue', newContent);
            });
        }
    };

    const finalConfig = { ...defaultConfig, ...props.config };
    tinymce.init(finalConfig);
};

const destroyEditor = () => {
    if (editor.value) {
        editor.value.destroy();
        editor.value = null;
    }
};

onMounted(() => {
    initEditor();
});

onBeforeUnmount(() => {
    destroyEditor();
});
</script>
