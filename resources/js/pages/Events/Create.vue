<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3'
import { ref } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue';

const form = useForm({
    title: '',
    description: '',
    start_date: '',
    end_date: '',
    link: '',
    image_path: null as File | null
})

const imagePreview = ref<string | null>(null)

const handleImageUpload = (event: Event) => {
    const target = event.target as HTMLInputElement
    const file = target.files?.[0]

    if (file) {
        form.image_path = file
        // Create preview URL
        const reader = new FileReader()
        reader.onload = (e) => {
            imagePreview.value = e.target?.result as string
        }
        reader.readAsDataURL(file)
    }
}

const removeImage = () => {
    form.image_path = null
    imagePreview.value = null
    // Reset the file input
    const fileInput = document.getElementById('image_path') as HTMLInputElement
    if (fileInput) fileInput.value = ''
}

const submit = () => {
    form.post('/events', {
        preserveScroll: true,
    })
}
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
                <form @submit.prevent="submit" class="bg-white shadow-xl rounded-lg overflow-hidden">
                    <div class="px-8 py-6">
                        <!-- Event Image Upload -->
                        <div class="mb-8">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">Event Image</label>

                            <!-- Image Preview or Upload Area -->
                            <div class="relative">
                                <div v-if="!imagePreview"
                                     class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-400 transition-colors duration-200">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <p class="text-gray-600 mb-2">Click to upload or drag and drop</p>
                                    <p class="text-sm text-gray-500">PNG, JPG, GIF up to 10MB</p>
                                    <input type="file"
                                           id="image_path"
                                           @change="handleImageUpload"
                                           accept="image/*"
                                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                </div>

                                <!-- Image Preview -->
                                <div v-else class="relative">
                                    <img :src="imagePreview" alt="Event preview" class="w-full h-64 object-cover rounded-lg">
                                    <button type="button"
                                            @click="removeImage"
                                            class="absolute top-2 right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-2 transition-colors duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <p v-if="form.errors.image_path" class="mt-1 text-sm text-red-600">{{ form.errors.image_path }}</p>
                        </div>

                        <!-- Event Title -->
                        <div class="mb-6">
                            <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">
                                Event Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="title"
                                   v-model="form.title"
                                   placeholder="Enter event title"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-gray-900"
                                   :class="form.errors.title ? 'border-red-500 ring-red-500' : ''"
                                   required>
                            <p v-if="form.errors.title" class="mt-1 text-sm text-red-600">{{ form.errors.title }}</p>
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
                                       v-model="form.start_date"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                       :class="form.errors.start_date ? 'border-red-500 ring-red-500' : ''"
                                       required>
                                <p v-if="form.errors.start_date" class="mt-1 text-sm text-red-600">{{ form.errors.start_date }}</p>
                            </div>

                            <!-- End Date -->
                            <div>
                                <label for="end_date" class="block text-sm font-semibold text-gray-700 mb-2">
                                    End Date & Time <span class="text-red-500">*</span>
                                </label>
                                <input type="datetime-local"
                                       id="end_date"
                                       v-model="form.end_date"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                       :class="form.errors.end_date ? 'border-red-500 ring-red-500' : ''"
                                       required>
                                <p v-if="form.errors.end_date" class="mt-1 text-sm text-red-600">{{ form.errors.end_date }}</p>
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
                                   v-model="form.link"
                                   placeholder="https://example.com/event"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                   :class="form.errors.link ? 'border-red-500 ring-red-500' : ''">
                            <p v-if="form.errors.link" class="mt-1 text-sm text-red-600">{{ form.errors.link }}</p>
                            <p class="mt-1 text-sm text-gray-500">Add a link to your event website, registration page, or more information</p>
                        </div>

                        <!-- Event Description -->
                        <div class="mb-8">
                            <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                                Event Description <span class="text-red-500">*</span>
                            </label>
                            <textarea id="description"
                                      v-model="form.description"
                                      rows="6"
                                      placeholder="Describe your event in detail. What can attendees expect? What should they bring?"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 resize-none"
                                      :class="form.errors.description ? 'border-red-500 ring-red-500' : ''"
                                      required></textarea>
                            <p v-if="form.errors.description" class="mt-1 text-sm text-red-600">{{ form.errors.description }}</p>
                            <div class="mt-1 flex justify-between items-center">
                                <p class="text-sm text-gray-500">Provide detailed information about your event</p>
                                <span class="text-sm text-gray-400">{{ form.description.length }} characters</span>
                            </div>
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
                            <button type="button"
                                    :disabled="form.processing"
                                    class="inline-flex justify-center items-center px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 disabled:from-gray-400 disabled:to-gray-500 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl disabled:shadow-none transition-all duration-300 transform hover:scale-105 disabled:scale-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                <svg v-if="form.processing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                {{ form.processing ? 'Coming soon...' : 'Create Event' }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
