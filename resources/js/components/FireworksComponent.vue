<template>
    <div v-if="showFireworks" class="fixed inset-0 z-50 pointer-events-none overflow-hidden">
        <!-- Fireworks Container -->
        <div ref="fireworksContainer" class="relative w-full h-full">
            <!-- Individual Firework -->
            <div
                v-for="firework in fireworks"
                :key="firework.id"
                :style="{
          left: firework.x + 'px',
          top: firework.y + 'px',
          position: 'absolute'
        }"
                class="firework-burst"
            >
                <!-- Particles -->
                <div
                    v-for="particle in firework.particles"
                    :key="particle.id"
                    :style="{
            transform: `translate(${particle.x}px, ${particle.y}px)`,
            backgroundColor: particle.color,
            animationDelay: particle.delay + 'ms',
            animationDuration: particle.duration + 'ms'
          }"
                    class="firework-particle"
                ></div>
            </div>
        </div>

        <!-- Welcome Message Overlay -->
        <div class="absolute inset-0 flex items-center justify-center">
            <div
                v-if="showWelcome"
                class="welcome-message bg-white bg-opacity-95 backdrop-blur-sm rounded-2xl shadow-2xl p-8 text-center max-w-md mx-4 animate-bounce-in"
            >
                <div class="text-6xl mb-4">🎉</div>
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Welcome!</h2>
                <p class="text-gray-600 mb-4">
                    Thanks for visiting our events page! Get ready for amazing experiences.
                </p>
                <button
                    @click="closeFireworks"
                    class="px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-300"
                >
                    Let's Explore Events!
                </button>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'

interface Particle {
    id: number
    x: number
    y: number
    color: string
    delay: number
    duration: number
}

interface Firework {
    id: number
    x: number
    y: number
    particles: Particle[]
}

const showFireworks = ref(false)
const showWelcome = ref(false)
const fireworks = ref<Firework[]>([])
const fireworksContainer = ref<HTMLElement>()

let animationFrameId: number | null = null
let timeoutIds: NodeJS.Timeout[] = []

const colors = [
    '#ff6b6b', '#4ecdc4', '#45b7d1', '#f9ca24', '#f0932b',
    '#eb4d4b', '#6c5ce7', '#a29bfe', '#fd79a8', '#fdcb6e',
    '#e17055', '#81ecec', '#74b9ff', '#fd79a8', '#55a3ff'
]

const isFirstVisit = () => {
    const hasVisited = localStorage.getItem('has_visited_events')
    if (!hasVisited) {
        localStorage.setItem('has_visited_events', 'true')
        return true
    }
    return false
}

const createParticles = (centerX: number, centerY: number): Particle[] => {
    const particles: Particle[] = []
    const particleCount = 12 + Math.floor(Math.random() * 8)

    for (let i = 0; i < particleCount; i++) {
        const angle = (i / particleCount) * Math.PI * 2
        const velocity = 80 + Math.random() * 60
        const x = Math.cos(angle) * velocity
        const y = Math.sin(angle) * velocity

        particles.push({
            id: i,
            x,
            y,
            color: colors[Math.floor(Math.random() * colors.length)],
            delay: Math.random() * 200,
            duration: 1000 + Math.random() * 500
        })
    }

    return particles
}

const createFirework = (x?: number, y?: number): Firework => {
    const containerWidth = window.innerWidth
    const containerHeight = window.innerHeight

    return {
        id: Date.now() + Math.random(),
        x: x || Math.random() * containerWidth,
        y: y || Math.random() * (containerHeight * 0.6) + containerHeight * 0.2,
        particles: createParticles(0, 0)
    }
}

const launchFireworks = () => {
    const fireworkCount = 5 + Math.floor(Math.random() * 3)

    for (let i = 0; i < fireworkCount; i++) {
        const delay = i * 300 + Math.random() * 200
        const timeoutId = setTimeout(() => {
            fireworks.value.push(createFirework())
        }, delay)
        timeoutIds.push(timeoutId)
    }

    // Clean up fireworks after animation
    const cleanupId = setTimeout(() => {
        fireworks.value = []
    }, 3000)
    timeoutIds.push(cleanupId)
}

const showWelcomeMessage = () => {
    const timeoutId = setTimeout(() => {
        showWelcome.value = true
    }, 1500)
    timeoutIds.push(timeoutId)
}

const closeFireworks = () => {
    showWelcome.value = false

    const timeoutId = setTimeout(() => {
        showFireworks.value = false
    }, 500)
    timeoutIds.push(timeoutId)
}

const startFireworksShow = () => {
    showFireworks.value = true
    launchFireworks()
    showWelcomeMessage()

    // Launch additional fireworks
    const additionalLaunchId = setTimeout(() => {
        launchFireworks()
    }, 2000)
    timeoutIds.push(additionalLaunchId)
}

onMounted(() => {
    if (isFirstVisit()) {
        startFireworksShow()
    }
})

onUnmounted(() => {
    if (animationFrameId) {
        cancelAnimationFrame(animationFrameId)
    }
    timeoutIds.forEach(id => clearTimeout(id))
})

// Method to manually trigger fireworks (for testing)
const triggerFireworks = () => {
    startFireworksShow()
}

defineExpose({
    triggerFireworks
})
</script>

<style scoped>
.firework-particle {
    position: absolute;
    width: 6px;
    height: 6px;
    border-radius: 50%;
    animation: particle-explode ease-out forwards;
    box-shadow: 0 0 6px currentColor;
}

.firework-burst {
    pointer-events: none;
}

.welcome-message {
    animation: fadeInScale 0.6s ease-out forwards;
    transform: scale(0.8);
    opacity: 0;
}

@keyframes particle-explode {
    0% {
        transform: translate(0, 0) scale(1);
        opacity: 1;
    }
    70% {
        opacity: 1;
        transform: translate(var(--x, 0), var(--y, 0)) scale(0.8);
    }
    100% {
        transform: translate(var(--x, 0), var(--y, 0)) scale(0);
        opacity: 0;
    }
}

@keyframes fadeInScale {
    to {
        transform: scale(1);
        opacity: 1;
    }
}

@keyframes bounce-in {
    0% {
        transform: scale(0.3);
        opacity: 0;
    }
    50% {
        transform: scale(1.05);
        opacity: 0.8;
    }
    70% {
        transform: scale(0.9);
        opacity: 0.9;
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

.animate-bounce-in {
    animation: bounce-in 0.6s ease-out forwards;
}

/* Dynamic particle animation */
.firework-particle {
    --x: 0px;
    --y: 0px;
}

.firework-particle:nth-child(1) { --x: 80px; --y: 0px; }
.firework-particle:nth-child(2) { --x: 56px; --y: 56px; }
.firework-particle:nth-child(3) { --x: 0px; --y: 80px; }
.firework-particle:nth-child(4) { --x: -56px; --y: 56px; }
.firework-particle:nth-child(5) { --x: -80px; --y: 0px; }
.firework-particle:nth-child(6) { --x: -56px; --y: -56px; }
.firework-particle:nth-child(7) { --x: 0px; --y: -80px; }
.firework-particle:nth-child(8) { --x: 56px; --y: -56px; }
.firework-particle:nth-child(9) { --x: 120px; --y: 20px; }
.firework-particle:nth-child(10) { --x: -120px; --y: -20px; }
.firework-particle:nth-child(11) { --x: 20px; --y: 120px; }
.firework-particle:nth-child(12) { --x: -20px; --y: -120px; }
.firework-particle:nth-child(13) { --x: 100px; --y: 60px; }
.firework-particle:nth-child(14) { --x: -100px; --y: 60px; }
.firework-particle:nth-child(15) { --x: 60px; --y: -100px; }
.firework-particle:nth-child(16) { --x: -60px; --y: 100px; }
.firework-particle:nth-child(17) { --x: 140px; --y: -30px; }
.firework-particle:nth-child(18) { --x: -140px; --y: 30px; }
.firework-particle:nth-child(19) { --x: 30px; --y: 140px; }
.firework-particle:nth-child(20) { --x: -30px; --y: -140px; }
</style>
