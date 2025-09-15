<template>
    <div class="slim circle">
        <img v-if="initialImage" :src="initialImage" alt="Profile Image" />
        <input type="file" name="slim[]" />
    </div>
</template>

<script setup lang="ts">
import Slim from './slim.module.js';
import { onMounted, onBeforeUnmount, defineProps, nextTick, watch } from 'vue';

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
        default: null, // Accept initial image URL
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
        type: String,
        default: '2',
    },
});

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
        container.setAttribute('data-max-file-size', props.maxFileSize);

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
        // Update the image in the existing instance
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

.slim {
    border-radius: 50%;
    overflow: hidden;
    width: 200px;
    height: 200px;
    margin: 0 auto;
}

.slim img {
    object-fit: cover;
    width: 100%;
    height: 100%;
}
</style>
