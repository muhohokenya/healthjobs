<template>
    <div
        class="slim circle"
        :style="containerStyles"
        :class="containerClass"
    >
        <img
            v-if="initialImage"
            :src="initialImage"
            alt="Profile Image"
            :style="imageStyles"
        />
        <input type="file" name="slim[]" />
    </div>
</template>

<script setup lang="ts">
import Slim from './slim.module.js';
import { onMounted, onBeforeUnmount, defineProps, nextTick, watch, computed } from 'vue';

// Define props to accept configuration from the parent
const props = defineProps({
    ratio: {
        type: String,
        default: '1:1',
    },
    size: {
        type: String,
        default: '240,240',
    },
    initialImage: {
        type: String,
        default: null,
    },
    service: {
        type: String,
        default: '/settings/update-profile',
    },
    edit: {
        type: Boolean,
        default: false,
    },
    fetcher: {
        type: String,
        default: 'fetch.php',
    },
    maxFileSize: {
        type: [String, Number],
        default: '2',
    },
    // Style props for container
    containerWidth: {
        type: String,
        default: '240px',
    },
    containerHeight: {
        type: String,
        default: '240px',
    },
    containerOverflow: {
        type: String,
        default: 'hidden',
    },
    containerMargin: {
        type: String,
        default: '0 auto',
    },
    containerBorderRadius: {
        type: String,
        default: '0',
    },
    containerBorder: {
        type: String,
        default: 'none',
    },
    containerBoxShadow: {
        type: String,
        default: 'none',
    },
    // Style props for image
    imageObjectFit: {
        type: String,
        default: 'cover',
    },
    imageWidth: {
        type: String,
        default: '100%',
    },
    imageHeight: {
        type: String,
        default: '100%',
    },
    // Alternative: Accept style objects directly
    containerStyle: {
        type: Object,
        default: () => ({}),
    },
    imageStyle: {
        type: Object,
        default: () => ({}),
    },
    // CSS class props
    containerClass: {
        type: [String, Array, Object],
        default: '',
    },
});

// Computed styles that merge default styles with props
const containerStyles = computed(() => ({
    overflow: props.containerOverflow,
    width: props.containerWidth,
    height: props.containerHeight,
    margin: props.containerMargin,
    borderRadius: props.containerBorderRadius,
    border: props.containerBorder,
    boxShadow: props.containerBoxShadow,
    ...props.containerStyle, // Allow override with style object
}));

const imageStyles = computed(() => ({
    objectFit: props.imageObjectFit,
    width: props.imageWidth,
    height: props.imageHeight,
    ...props.imageStyle, // Allow override with style object
}));

// Initialize SlimCropper instance
let instance = null;

const initializeSlim = () => {
    const container = document.querySelector('.slim');
    if (container && !instance) {
        const options = {
            ratio: props.ratio,
            size: props.size,
            maxFileSize: props.maxFileSize,
            label: 'Drop your image here or click to browse',
        };

        // Add data attributes to the container
        container.setAttribute('data-ratio', props.ratio);
        container.setAttribute('data-size', props.size);
        container.setAttribute('data-service', props.service);
        container.setAttribute('data-edit', String(props.edit));
        container.setAttribute('data-fetcher', props.fetcher);
        container.setAttribute('data-max-file-size', String(props.maxFileSize));

        instance = new Slim(container, options);
    }
};

onMounted(() => {
    nextTick(() => {
        initializeSlim();
    });
});

// Watch for changes in initialImage prop
watch(() => props.initialImage, (newImage) => {
    if (newImage && instance) {
        instance.load(newImage);
    }
});

onBeforeUnmount(() => {
    if (instance) {
        instance.destroy();
    }
});
</script>

<style lang="css">
@import './slim.min.css';

/* Base styles - can be overridden by props */
.slim {
    position: relative;
}
</style>
