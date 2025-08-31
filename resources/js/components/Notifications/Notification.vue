<script setup lang="ts">
import { ref } from "vue"

const props = defineProps<{
    user: string
}>()

// Example: notifications for the user
const notifications = ref([
    { id: 1, message: `Welcome ${props.user}!`, type: "info" },
    { id: 2, message: "You have 3 new messages", type: "success" },
])
</script>

<template>
    <div class="fixed top-4 right-4 space-y-3 z-50">
        <div
            v-for="note in notifications"
            :key="note.id"
            class="rounded-xl shadow-md px-4 py-2 text-white"
            :class="{
        'bg-blue-500': note.type === 'info',
        'bg-green-500': note.type === 'success',
        'bg-red-500': note.type === 'error',
      }"
        >
            {{ note.message }}
        </div>
    </div>
</template>
