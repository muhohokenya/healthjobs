<script setup lang="ts">
import { ref } from 'vue'

const showLogin = ref(false)
const showRegister = ref(false)
const mobileMenuOpen = ref(false)

const stats = [
    { number: '15,000+', label: 'Medical Jobs' },
    { number: '8,500+', label: 'Healthcare Facilities' },
    { number: '25,000+', label: 'Registered Doctors' },
    { number: '98%', label: 'Success Rate' }
]

const features = [
    {
        icon: '🩺',
        title: 'Smart Job Matching',
        description: 'AI-powered matching system connects you with perfect opportunities based on your specialty and preferences.'
    },
    {
        icon: '🏥',
        title: 'Verified Healthcare Facilities',
        description: 'Only work with pre-screened, reputable hospitals and clinics across the country.'
    },
    {
        icon: '📊',
        title: 'Career Analytics',
        description: 'Track your application progress and get insights to boost your medical career.'
    },
    {
        icon: '⚡',
        title: 'Instant Applications',
        description: 'Apply to multiple positions with one click using your verified medical profile.'
    }
]

const navigateToLogin = () => {
    window.location.href = '/login'
}

const navigateToRegister = () => {
    window.location.href = '/register'
}
</script>

<template>
    <!-- Navigation -->
    <nav class="fixed w-full z-50 bg-white/90 backdrop-blur-lg border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-2">
                    <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl flex items-center justify-center">
                        <span class="text-white font-bold text-xl">M</span>
                    </div>
                    <span class="font-bold text-xl bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">MediJobs</span>
                </div>

                <div class="hidden md:flex items-center space-x-8">
                    <a href="#" class="text-gray-700 hover:text-blue-600 transition-colors font-medium">Jobs</a>
                    <a href="#" class="text-gray-700 hover:text-blue-600 transition-colors font-medium">Hospitals</a>
                    <a href="#" class="text-gray-700 hover:text-blue-600 transition-colors font-medium">About</a>
                    <a href="#" class="text-gray-700 hover:text-blue-600 transition-colors font-medium">Contact</a>
                </div>

                <div class="flex items-center space-x-4">
                    <button @click="navigateToLogin" class="hidden md:block text-gray-700 hover:text-blue-600 transition-colors font-medium">
                        Login
                    </button>
                    <button @click="navigateToRegister" class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-2 rounded-full hover:shadow-lg transform hover:scale-105 transition-all duration-200 font-medium">
                        Get Started
                    </button>
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="pt-24 pb-16 bg-gradient-to-br from-blue-50 via-white to-purple-50 min-h-screen flex items-center">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-4xl mx-auto">
                <h1 class="text-5xl md:text-7xl font-bold mb-6">
                    Your Medical Career
                    <span class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent block">
            Starts Here
          </span>
                </h1>
                <p class="text-xl md:text-2xl text-gray-600 mb-12 leading-relaxed">
                    Connect with top healthcare facilities, discover your dream medical position, and advance your career with the most trusted platform for medical professionals.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-16">
                    <button @click="navigateToRegister" class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-4 rounded-full text-lg font-semibold hover:shadow-2xl transform hover:scale-105 transition-all duration-300">
                        Find Your Dream Job
                    </button>
                    <button class="border-2 border-gray-300 text-gray-700 px-8 py-4 rounded-full text-lg font-semibold hover:border-blue-600 hover:text-blue-600 transition-all duration-300">
                        Watch Demo
                    </button>
                </div>

                <!-- Stats -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mt-20">
                    <div v-for="stat in stats" :key="stat.label" class="text-center">
                        <div class="text-3xl md:text-4xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                            {{ stat.number }}
                        </div>
                        <div class="text-gray-600 font-medium mt-2">{{ stat.label }}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold mb-6">
                    Why Choose
                    <span class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">MediJobs?</span>
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    We're revolutionizing how medical professionals find opportunities and how healthcare facilities discover talent.
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div v-for="feature in features" :key="feature.title" class="bg-gradient-to-br from-white to-gray-50 p-8 rounded-2xl border border-gray-100 hover:shadow-xl hover:border-blue-200 transition-all duration-300 group">
                    <div class="text-4xl mb-4 group-hover:scale-110 transition-transform duration-300">{{ feature.icon }}</div>
                    <h3 class="text-xl font-bold mb-4 text-gray-900">{{ feature.title }}</h3>
                    <p class="text-gray-600 leading-relaxed">{{ feature.description }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-r from-blue-600 to-purple-600">
        <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
            <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">
                Ready to Transform Your Medical Career?
            </h2>
            <p class="text-xl text-blue-100 mb-10">
                Join thousands of medical professionals who've found their perfect match through MediJobs.
            </p>
            <button @click="navigateToRegister" class="bg-white text-blue-600 px-10 py-4 rounded-full text-lg font-bold hover:shadow-2xl transform hover:scale-105 transition-all duration-300">
                Start Your Journey Today
            </button>
        </div>
    </section>


</template>
