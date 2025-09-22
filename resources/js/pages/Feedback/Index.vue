<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue';
</script>

<template>
    <AppLayout>
        <Head title="Share Your Feedback" />

        <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-green-50 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto">
                <!-- Header Section -->
                <div class="text-center mb-12">
                    <div class="flex justify-center mb-6">
                        <div class="bg-gradient-to-r from-blue-500 to-green-500 rounded-full p-4">
                            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                        </div>
                    </div>
                    <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                        We'd Love to Hear
                        <span class="bg-gradient-to-r from-blue-600 to-green-600 bg-clip-text text-transparent">From You!</span>
                    </h1>
                    <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                        Your thoughts matter to us. Help us make our app even better by sharing your experience and suggestions.
                    </p>
                </div>

                <!-- Main Feedback Card -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden mb-8">
                    <div class="p-8 md:p-12">
                        <div class="grid md:grid-cols-2 gap-8 items-center">
                            <!-- Left side - Content -->
                            <div>
                                <h2 class="text-3xl font-bold text-gray-900 mb-6">Ready to Share?</h2>
                                <div class="space-y-4 mb-8">
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0 w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center mt-1">
                                            <svg class="w-3 h-3 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <p class="text-gray-700">How do you feel about using our app?</p>
                                    </div>
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0 w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mt-1">
                                            <svg class="w-3 h-3 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <p class="text-gray-700">What features do you love most?</p>
                                    </div>
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0 w-6 h-6 bg-purple-100 rounded-full flex items-center justify-center mt-1">
                                            <svg class="w-3 h-3 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <p class="text-gray-700">What could we improve or add?</p>
                                    </div>
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0 w-6 h-6 bg-orange-100 rounded-full flex items-center justify-center mt-1">
                                            <svg class="w-3 h-3 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <p class="text-gray-700">Any bugs or issues you've encountered?</p>
                                    </div>
                                </div>

                                <a
                                    href="https://wa.me/254712419949?text=Hi! I'd like to share my feedback about the app:"
                                    class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200"
                                >
                                    <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.687"/>
                                    </svg>
                                    Chat with us on WhatsApp
                                </a>
                            </div>

                            <!-- Right side - Illustration -->
                            <div class="hidden md:block">
                                <div class="bg-gradient-to-br from-blue-100 to-green-100 rounded-2xl p-8 text-center">
                                    <div class="text-6xl mb-4">💬</div>
                                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Quick & Easy</h3>
                                    <p class="text-gray-600">Just one tap to start the conversation!</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Alternative Contact -->
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-6 text-center">
                    <p class="text-gray-600 mb-4">
                        Prefer a different way to reach us? You can also send us a message at
                    </p>
                    <div class="flex flex-col sm:flex-row items-center justify-center space-y-2 sm:space-y-0 sm:space-x-4">
                        <span class="font-semibold text-gray-800">📱 0712419949</span>
                        <span class="text-gray-400 hidden sm:inline">•</span>
                        <span class="text-sm text-gray-600">Available Monday - Friday, 9 AM - 6 PM EAT</span>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
