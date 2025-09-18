<script setup lang="ts">
import { ref } from 'vue';
import TextLink from '@/components/TextLink.vue';

const cancelMessage = ref(false);

</script>

<template>
    <div v-if="!cancelMessage">
        <div class="p-6 bg-green-50 rounded-lg border border-blue-200 mb-4">
            <p class="text-md text-gray-700 mb-4">
                Keep your profile updated and never miss your next opportunity!
            </p>

            <div class="flex items-center gap-4">
                <TextLink href="/settings/profile" class="text-indigo-600 font-medium hover:underline">
                    👉 Go to Your Profile
                </TextLink>
            </div>
        </div>
    </div>
</template>
