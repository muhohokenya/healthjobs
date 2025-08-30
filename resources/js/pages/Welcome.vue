<script setup lang="ts">
import { ref } from 'vue';

const mobileMenuOpen = ref(false);

const features = [
    {
        icon: '🩺',
        title: 'Smart Job Matching',
        description: 'AI-powered matching system connects you with perfect opportunities based on your specialty and preferences.',
    },
    {
        icon: '🏥',
        title: 'Verified Healthcare Facilities',
        description: 'Only work with pre-screened, reputable hospitals and clinics across the country.',
    },
    {
        icon: '📊',
        title: 'Career Analytics',
        description: 'Track your application progress and get insights to boost your medical career.',
    },
    {
        icon: '⚡',
        title: 'Instant Applications',
        description: 'Apply to multiple positions with one click using your verified medical profile.',
    },
];

const navigateToLogin = (): void => {
    window.location.href = '/login';
};

const navigateToRegister = (): void => {
    window.location.href = '/register';
};
</script>

<template>
    <!-- Navigation -->
    <nav class="fixed w-full z-50 bg-white/95 backdrop-blur-lg border-b border-gray-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19 8h-2v3h-3v2h3v3h2v-3h3v-2h-3V8zM4 8h5v12H4z"/>
                            <path d="M10 8h5v3h-5z"/>
                            <circle cx="7" cy="6" r="2"/>
                        </svg>
                    </div>
                    <span class="font-bold text-xl bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">MediCareers</span>
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
                    <button @click="navigateToRegister" class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-2.5 rounded-full hover:shadow-lg transform hover:scale-105 transition-all duration-200 font-medium">
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
    <section class="relative pt-20 pb-32 bg-gradient-to-br from-blue-50 via-white to-purple-50 overflow-hidden">
        <!-- Floating Background Elements -->
        <div class="absolute top-20 left-10 w-20 h-20 bg-blue-200/30 rounded-full animate-bounce"></div>
        <div class="absolute top-32 right-20 w-16 h-16 bg-purple-200/30 rounded-full animate-pulse"></div>
        <div class="absolute bottom-40 left-20 w-12 h-12 bg-green-200/30 rounded-full animate-ping"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid lg:grid-cols-2 gap-12 items-center min-h-screen py-20">
                <!-- Left Content -->
                <div class="space-y-8">
                    <div class="inline-flex items-center px-4 py-2 bg-blue-100 rounded-full text-blue-700 text-sm font-medium">
                        ✨ Trusted by 25,000+ Medical Professionals
                    </div>

                    <h1 class="text-5xl md:text-7xl font-bold leading-tight">
                        Transform Your
                        <span class="bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600 bg-clip-text text-transparent block">
                            Medical Career
                        </span>
                    </h1>

                    <p class="text-xl text-gray-600 leading-relaxed max-w-lg">
                        Connect with premier healthcare facilities, discover opportunities that match your expertise, and advance your career with AI-powered job matching.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4">
                        <button @click="navigateToRegister" class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-4 rounded-full text-lg font-semibold hover:shadow-2xl transform hover:scale-105 transition-all duration-300 flex items-center justify-center space-x-2">
                            <span>Find Your Dream Job</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </button>
                        <button class="border-2 border-gray-300 text-gray-700 px-8 py-4 rounded-full text-lg font-semibold hover:border-blue-600 hover:text-blue-600 transition-all duration-300 flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1M12 6v4"></path>
                            </svg>
                            <span>Watch Demo</span>
                        </button>
                    </div>

                    <!-- Mini Stats -->
                    <div class="grid grid-cols-3 gap-6 pt-8">
                        <div class="text-center">
                            <div class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">15K+</div>
                            <div class="text-gray-600 text-sm">Jobs Posted</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">8.5K+</div>
                            <div class="text-gray-600 text-sm">Hospitals</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">98%</div>
                            <div class="text-gray-600 text-sm">Success Rate</div>
                        </div>
                    </div>
                </div>

                <!-- Right Content - Hero Images -->
                <div class="relative">
                    <div class="relative z-10">
                        <!-- Main Hero Image -->
                        <div class="relative group">
                            <div class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl blur opacity-30 group-hover:opacity-50 transition duration-1000"></div>
                            <div class="relative bg-white rounded-2xl overflow-hidden shadow-2xl">
                                <img src="https://images.unsplash.com/photo-1559757148-5c350d0d3c56?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80"
                                     alt="Medical professionals caring for patients"
                                     class="w-full h-96 object-cover">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                            </div>
                        </div>

                        <!-- Floating Cards -->
                        <div class="absolute -top-6 -left-6 bg-white rounded-xl p-4 shadow-lg animate-bounce">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-gray-900">Job Match</div>
                                    <div class="text-xs text-gray-500">98% Compatible</div>
                                </div>
                            </div>
                        </div>

                        <div class="absolute -bottom-4 -right-4 bg-white rounded-xl p-4 shadow-lg animate-pulse">
                            <div class="flex items-center space-x-3">
                                <img src="https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=100&q=80"
                                     alt="Doctor" class="w-10 h-10 rounded-full object-cover">
                                <div>
                                    <div class="text-sm font-semibold text-gray-900">Dr. Sarah Chen</div>
                                    <div class="text-xs text-gray-500">Just got hired!</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Wave Bottom using CSS clip-path -->
        <div class="absolute bottom-0 left-0 w-full overflow-hidden leading-none">
            <svg class="relative block w-full h-24" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" opacity=".25" fill="#ffffff"></path>
                <path d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z" opacity=".5" fill="#ffffff"></path>
                <path d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z" fill="#ffffff"></path>
            </svg>
        </div>
    </section>

    <!-- Hello Section -->
    <section class="relative bg-white py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <div class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-100 to-purple-100 rounded-full text-blue-700 text-sm font-medium mb-6">
                    👋 Hello, Future Healthcare Hero
                </div>
                <h2 class="text-4xl md:text-6xl font-bold mb-6">
                    Every Patient Deserves
                    <span class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent block">
                        Exceptional Care
                    </span>
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                    Join a community of dedicated medical professionals making a difference every day. Your next opportunity to save lives and build your legacy awaits.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8 mb-16">
                <!-- Care Image 1 -->
                <div class="relative group transform hover:scale-105 transition-all duration-500">
                    <div class="absolute -inset-1 bg-gradient-to-r from-pink-600 to-purple-600 rounded-2xl blur opacity-20 group-hover:opacity-40 transition duration-500"></div>
                    <div class="relative bg-white rounded-2xl overflow-hidden shadow-xl">
                        <img src="https://media.istockphoto.com/id/1719538017/photo/home-care-healthcare-professional-hugging-senior-patient.jpg?s=612x612&w=0&k=20&c=DTQwVD1DTH0CMQ78aox8-cVKg8Nl-wCkSwY-S072M4E="
                             alt="Doctor caring for elderly patient"
                             class="w-full h-64 object-cover">
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Compassionate Care</h3>
                            <p class="text-gray-600">Building trust and healing relationships with every interaction</p>
                        </div>
                    </div>
                </div>

                <!-- Care Image 2 -->
                <div class="relative group transform hover:scale-105 transition-all duration-500">
                    <div class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-cyan-600 rounded-2xl blur opacity-20 group-hover:opacity-40 transition duration-500"></div>
                    <div class="relative bg-white rounded-2xl overflow-hidden shadow-xl">
                        <img src="https://images.unsplash.com/photo-1631815588090-d4bfec5b1ccb?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80"
                             alt="Medical team collaboration"
                             class="w-full h-64 object-cover">
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Team Excellence</h3>
                            <p class="text-gray-600">Collaborative care that brings out the best in medical teams</p>
                        </div>
                    </div>
                </div>

                <!-- Care Image 3 -->
                <div class="relative group transform hover:scale-105 transition-all duration-500">
                    <div class="absolute -inset-1 bg-gradient-to-r from-green-600 to-blue-600 rounded-2xl blur opacity-20 group-hover:opacity-40 transition duration-500"></div>
                    <div class="relative bg-white rounded-2xl overflow-hidden shadow-xl">
                        <img src="https://images.unsplash.com/photo-1582750433449-648ed127bb54?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80"
                             alt="Pediatric care with children"
                             class="w-full h-64 object-cover">
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Future Focused</h3>
                            <p class="text-gray-600">Nurturing the next generation with advanced medical care</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Call to Action in Hello Section -->
            <div class="text-center">
                <button @click="navigateToRegister" class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-10 py-4 rounded-full text-lg font-semibold hover:shadow-2xl transform hover:scale-105 transition-all duration-300">
                    Start Making a Difference Today
                </button>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-24 bg-slate-50 relative">
        <!-- Wave Top -->
        <div class="absolute top-0 left-0 w-full overflow-hidden leading-none rotate-180">
            <svg class="relative block w-full h-24" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M985.66,92.83C906.67,72,823.78,31,743.84,14.19c-82.26-17.34-168.06-16.33-250.45.39-57.84,11.73-114,31.07-172,41.86A600.21,600.21,0,0,1,0,27.35V120H1200V95.8C1132.19,118.92,1055.71,111.31,985.66,92.83Z" fill="#f8fafc"></path>
            </svg>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold mb-6">
                    Why Choose
                    <span class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">MediCareers?</span>
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Advanced technology meets human-centered design to create the ultimate platform for medical career growth.
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div v-for="feature in features" :key="feature.title"
                     class="bg-gradient-to-br from-white to-gray-50 p-8 rounded-2xl border border-gray-100 hover:shadow-xl hover:border-blue-200 transition-all duration-300 group transform hover:scale-105">
                    <div class="text-4xl mb-4 group-hover:scale-110 transition-transform duration-300">{{ feature.icon }}</div>
                    <h3 class="text-xl font-bold mb-4 text-gray-900">{{ feature.title }}</h3>
                    <p class="text-gray-600 leading-relaxed">{{ feature.description }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-24 bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-700 relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-10 left-10 w-32 h-32 bg-white rounded-full animate-pulse"></div>
            <div class="absolute bottom-20 right-20 w-24 h-24 bg-white rounded-full animate-bounce"></div>
            <div class="absolute top-1/2 left-1/3 w-16 h-16 bg-white rounded-full animate-ping"></div>
        </div>

        <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8 relative z-10">
            <h2 class="text-4xl md:text-6xl font-bold text-white mb-6 leading-tight">
                Ready to Transform Your
                <span class="block bg-gradient-to-r from-yellow-300 to-pink-300 bg-clip-text text-transparent">
                    Medical Career?
                </span>
            </h2>
            <p class="text-xl text-blue-100 mb-12 leading-relaxed">
                Join thousands of medical professionals who've found their perfect match through MediCareers. Your dream position is just one click away.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <button @click="navigateToRegister" class="bg-white text-blue-600 px-10 py-4 rounded-full text-lg font-bold hover:shadow-2xl transform hover:scale-105 transition-all duration-300 flex items-center space-x-2">
                    <span>Start Your Journey Today</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </button>
                <button class="border-2 border-white text-white px-10 py-4 rounded-full text-lg font-semibold hover:bg-white hover:text-blue-600 transition-all duration-300">
                    Learn More
                </button>
            </div>
        </div>
    </section>
</template>
