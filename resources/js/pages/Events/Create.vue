<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue';
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import SlimCropper from './../../lib/slim/SlimCropper.vue';

// Define the options for the cropper
const cropperOptions = ref({
    initialImage: '',
    ratio: '3:2',
    size: '600,440',
});

// Define custom styles for the cropper
const cropperStyles = {
    containerWidth: '700px',
    containerHeight: '400px',
    containerOverflow: 'hidden',
    containerMargin: '',
    containerBorderRadius: '8px',
    containerBoxShadow: '0 4px 6px -1px rgba(0, 0, 0, 0.1)',
    imageObjectFit: 'cover',
    imageWidth: '100%',
    imageHeight: '100%',
};

// Alternative: Define as style objects
// const containerStyleObject = {
//     overflow: 'hidden',
//     width: '500px',
//     height: '500px',
//     margin: '',
//     borderRadius: '8px',
//     boxShadow: '0 4px 6px -1px rgba(0, 0, 0, 0.1)',
//     border: '2px dashed #e5e7eb',
//     transition: 'border-color 0.2s ease',
// };

// const imageStyleObject = {
//     objectFit: 'cover',
//     width: '100%',
//     height: '100%',
// };

// Form data
const formData = ref({
    title: '',
    description: '',
    start_date: '',
    end_date: '',
    link: '',
    image_path: null as File | null
});
</script>

<template>
    <AppLayout>
        <Head title="Create Event" />

        <div class="min-h-screen bg-gray-50 py-8">
            <div class="max-w-4xl px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Create New Event</h1>
                    <p class="text-gray-600">Fill in the details below to create your event</p>
                </div>

                <!-- Form -->
                <Form :data="formData" method="post" action="/events" preserve-scroll class="bg-white shadow-xl rounded-lg overflow-hidden">
                    <div class="px-8 py-6">
                        <!-- Event Image Upload -->
                        <div class="mb-8">
                            <!-- Method 1: Using individual style props -->
                            <slim-cropper
                                id="event_image"
                                :ratio="cropperOptions.ratio"
                                :size="cropperOptions.size"
                                :initial-image="cropperOptions.initialImage"
                                :container-width="cropperStyles.containerWidth"
                                :container-height="cropperStyles.containerHeight"
                                :container-overflow="cropperStyles.containerOverflow"
                                :container-margin="cropperStyles.containerMargin"
                                :container-border-radius="cropperStyles.containerBorderRadius"
                                :container-box-shadow="cropperStyles.containerBoxShadow"
                                :image-object-fit="cropperStyles.imageObjectFit"
                                :image-width="cropperStyles.imageWidth"
                                :image-height="cropperStyles.imageHeight"
                                :max-file-size="5"
                                class="w-full"
                            />

                            <!-- Method 2: Using style objects (cleaner approach) -->
                            <!-- <slim-cropper
                                :ratio="cropperOptions.ratio"
                                :size="cropperOptions.size"
                                :initial-image="cropperOptions.initialImage"
                                :container-style="containerStyleObject"
                                :image-style="imageStyleObject"
                                :max-file-size="5"
                                class="w-full"
                            /> -->

                            <!-- Method 3: Using CSS classes -->
                            <!-- <slim-cropper
                                :ratio="cropperOptions.ratio"
                                :size="cropperOptions.size"
                                :initial-image="cropperOptions.initialImage"
                                :max-file-size="5"
                                container-class="custom-cropper-container"
                                class="w-full"
                            /> -->

                            <p v-if="$page.props.errors.image_path" class="mt-1 text-sm text-red-600">{{ $page.props.errors.image_path }}</p>
                        </div>

                        <!-- Rest of your form fields... -->
                        <!-- Event Title -->
                        <div class="mb-6">
                            <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">
                                Event Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="title"
                                   name="title"
                                   v-model="formData.title"
                                   placeholder="Enter event title"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-gray-900"
                                   :class="$page.props.errors.title ? 'border-red-500 ring-red-500' : ''"
                                   required>
                            <p v-if="$page.props.errors.title" class="mt-1 text-sm text-red-600">{{ $page.props.errors.title }}</p>
                        </div>

                        <!-- Date and Time Section -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Start Date -->
                            <div>
                                <label for="start_date" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Start Date & Time <span class="text-red-500">*</span>
                                </label>
                                <input type="datetime-local"
                                       id="start_date"
                                       name="start_date"
                                       v-model="formData.start_date"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                       :class="$page.props.errors.start_date ? 'border-red-500 ring-red-500' : ''"
                                       required>
                                <p v-if="$page.props.errors.start_date" class="mt-1 text-sm text-red-600">{{ $page.props.errors.start_date }}</p>
                            </div>

                            <!-- End Date -->
                            <div>
                                <label for="end_date" class="block text-sm font-semibold text-gray-700 mb-2">
                                    End Date & Time <span class="text-red-500">*</span>
                                </label>
                                <input type="datetime-local"
                                       id="end_date"
                                       name="end_date"
                                       v-model="formData.end_date"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                       :class="$page.props.errors.end_date ? 'border-red-500 ring-red-500' : ''"
                                       required>
                                <p v-if="$page.props.errors.end_date" class="mt-1 text-sm text-red-600">{{ $page.props.errors.end_date }}</p>
                            </div>
                        </div>

                        <!-- Event Link -->
                        <div class="mb-6">
                            <label for="link" class="block text-sm font-semibold text-gray-700 mb-2">
                                Event Link
                                <span class="text-gray-500 font-normal">(Optional)</span>
                            </label>
                            <input type="url"
                                   id="link"
                                   name="link"
                                   v-model="formData.link"
                                   placeholder="https://example.com/event"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                   :class="$page.props.errors.link ? 'border-red-500 ring-red-500' : ''">
                            <p v-if="$page.props.errors.link" class="mt-1 text-sm text-red-600">{{ $page.props.errors.link }}</p>
                            <p class="mt-1 text-sm text-gray-500">Add a link to your event website, registration page, or more information</p>
                        </div>

                        <!-- Event Description -->
                        <div class="mb-8">
                            <label for="description" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Event Description <small class="text-red-700">*</small>
                            </label>
                            <QuillEditor
                                id="description"
                                content-type="html"
                                v-model:content="formData.description"
                                placeholder="Describe your event..."
                                theme="snow"
                                toolbar="full"
                                class="rounded-lg bg-white dark:bg-gray-700"
                            />
                            <input type="hidden" name="description" :value="formData.description" />
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="px-8 py-6 bg-gray-50 border-t border-gray-200">
                        <div class="flex flex-col sm:flex-row sm:justify-end sm:space-x-4 space-y-3 sm:space-y-0">
                            <!-- Cancel Button -->
                            <a href="/events"
                               class="inline-flex justify-center items-center px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200">
                                Cancel
                            </a>

                            <!-- Submit Button -->
                            <button type="submit"
                                    class="inline-flex justify-center items-center px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 disabled:from-gray-400 disabled:to-gray-500 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl disabled:shadow-none transition-all duration-300 transform hover:scale-105 disabled:scale-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                Create Event
                            </button>
                        </div>
                    </div>
                </Form>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
/* Method 3: Custom CSS classes approach */
:deep(.custom-cropper-container) {
    overflow: hidden;
    width: 500px;
    height: 500px;
    margin: 0 auto;
    border-radius: 8px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

:deep(.custom-cropper-container img) {
    object-fit: cover;
    width: 100%;
    height: 100%;
}

/* Existing styles */
:deep(.ql-editor) {
    min-height: 120px;
    font-size: 14px;
}

:deep(.ql-toolbar) {
    border-top-left-radius: 0.5rem;
    border-top-right-radius: 0.5rem;
}

:deep(.ql-container) {
    border-bottom-left-radius: 0.5rem;
    border-bottom-right-radius: 0.5rem;
}

:deep(.ql-container.ql-snow) {
    border-color: inherit;
}
</style>
