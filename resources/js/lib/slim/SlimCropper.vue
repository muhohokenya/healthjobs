<template>
    <div class="slim circle">
        <slot></slot>
    </div>
</template>

<script setup lang="ts">
import Slim from './slim.module.js';
import { ref, onMounted, onBeforeUnmount, defineProps, nextTick } from 'vue';

// Define props to accept `ratio` and `size` from the parent
const props = defineProps({
    ratio: {
        type: String,
        default: '1:1',  // Default ratio
    },
    size: {
        type: String,
        default: '240,240',  // Default size
    },
});

// Initialize SlimCropper instance
let instance = null;

const cropperOptions = ref({
    initialImage: 'https://placeholder.pics/svg/300',  // Example image
    ratio: props.ratio,  // Use the `ratio` prop from parent
    minWidth: 100,  // Minimum crop width
    minHeight: 100,  // Minimum crop height
    label: 'Drop your avatar here',  // Data label
    fetcher: 'fetch.php',  // Data fetcher URL
    size: props.size,  // Use the `size` prop from parent
    ratioValue: props.ratio,  // Data ratio (could be used if necessary)
});

onMounted(() => {
    nextTick(() => {
        const container = document.querySelector('.slim');
        if (container) {
            // Ensure the image loads successfully before initializing SlimCropper
            const image = new Image();
            image.onload = () => {
                instance = new Slim(container, cropperOptions.value);
            };
            image.onerror = (err) => {
                console.error('Image failed to load', err);
            };
            image.src = cropperOptions.value.initialImage;
        } else {
            console.error("SlimCropper container element not found!");
        }
    });
});

onBeforeUnmount(() => {
    if (instance) {
        instance.destroy();
    }
});
</script>

<style lang="css">
@import './slim.min.css';

/* Circle Styling */
.slim {
    border-radius: 50%;  /* Ensure the container is a circle */
    overflow: hidden;    /* Hide the overflow so the cropper fits perfectly within the circle */
}

.slim img {
    object-fit: cover;  /* Ensure the image is cropped to fit the circle */
}
</style>
