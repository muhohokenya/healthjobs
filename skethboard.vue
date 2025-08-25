<!-- Licence Field -->
<div class="space-y-2">
<Label for="name" class="text-sm font-semibold text-gray-700">PBB License</Label>
<!-- Medium - 32x32px -->
<div class="flex items-center gap-3">
    <img
        src="https://practice.pharmacyboardkenya.org/assets/img/gok_logo.png"
        class="h-8 w-8 rounded-full object-cover"
    />
    <span class="text-sm">Enter your <b>PHARMACY AND POISONS BOARD</b> Licence</span>
</div>
<div class="relative">
    <Input

        id="licence_number"
        type="text"
        autofocus
        :tabindex="1"
        autocomplete="off"
        name="licence_number"
        placeholder="PT20***** MUST BE 12 characters long"
        class="h-12 w-full rounded-lg border-2 border-gray-200 bg-white/50 px-4 py-3 backdrop-blur-sm transition-all duration-200 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500"
    />
    <small class="ml-2 text-red-400">Please Note that this will be verified againsts the PPB Register</small>
    <div v-if="errors.licence_number" class="mt-1 text-sm text-red-500">{{ errors.licence_number }}</div>
    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
        <svg class=" w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
            ></path>
        </svg>
    </div>
</div>
<InputError :message="errors.name" />
</div>


<!--'licence_number' => [
                'nullable',
                'size:11',
                'unique:facilities,licence_number'
            ],-->


<!-- Name Field -->
<div class="space-y-2">
<Label for="name" class="text-sm font-semibold text-gray-700">Full Name</Label>
<div class="relative">
    <Input
        id="name"
        type="text"
        required
        autofocus
        :tabindex="1"
        autocomplete="off"
        name="name"
        placeholder="Dr. John Doe"
        class="h-12 w-full rounded-lg border-2 border-gray-200 bg-white/50 px-4 py-3 backdrop-blur-sm transition-all duration-200 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500"
    />
    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
            ></path>
        </svg>
    </div>
</div>
<InputError :message="errors.name" />
</div>
<script setup lang="ts">
import { Label } from './resources/js/components/ui/label';
import { Input } from './resources/js/components/ui/input';
import InputError from './resources/js/components/InputError.vue';
</script>
