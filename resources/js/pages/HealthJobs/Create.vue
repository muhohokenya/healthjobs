<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Form, useForm } from '@inertiajs/vue3';
import { useAuth } from '@/utils/auth';
import { ref, computed, watch } from 'vue';
import { sortedCounties } from '@/utils/counties';
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css'

const user = useAuth();

// Separate form for image upload
const uploadForm = useForm({
    image: null,
});

// Main job form
const jobForm = useForm({
    title: '',
    description: '',
    job_type: '',
    location: '',
    salary_min: null,
    salary_max: null,
    qualifications: [],
    is_active: true
});

const props = defineProps({
    data: Object,
    success: Boolean,
    raw_response: String
});

// Upload processing state
const isProcessing = ref(false);
const aiResponse = ref(null);

// Dynamic qualifications list
const qualifications = ref([]);
const maxQualifications = 15;
const description = ref('');

// Computed property to check if we can add more qualifications
const canAddMore = computed(() => {
    return qualifications.value.length < maxQualifications;
});

// Add a new qualification input
const addQualification = () => {
    if (canAddMore.value) {
        qualifications.value.push('');
    }
};

// Remove a qualification input
const removeQualification = (index: number) => {
    qualifications.value.splice(index, 1);
};

// Handle image upload and AI processing
async function uploadAndProcess() {
    if (!uploadForm.image) {
        alert('Please select an image first');
        return;
    }

    isProcessing.value = true;

    try {
        // Upload and process image
        await uploadForm.post('/health-jobs/upload', {
            onSuccess: (page) => {
                // Extract data from the response
                const responseData = page.props.data;
                if (responseData) {
                    populateFormWithAIData(responseData);
                    aiResponse.value = responseData;
                }
            },
            onError: (errors) => {
                console.error('Upload failed:', errors);
                alert('Failed to process image. Please try again.');
            },
            onFinish: () => {
                isProcessing.value = false;
            }
        });
    } catch (error) {
        console.error('Error:', error);
        isProcessing.value = false;
        alert('An error occurred while processing the image.');
    }
}

// Populate form with AI response data
function populateFormWithAIData(data) {
    if (data.title) jobForm.title = data.title;
    if (data.description) {
        description.value = data.description;
        jobForm.description = data.description;
    }
    if (data.job_type) {
        const jobType = data.job_type.toLowerCase().includes('full') ? 'full-time' : 'part-time';
        jobForm.job_type = jobType;
    }
    if (data.location) jobForm.location = data.location;
    if (data.salary_min) jobForm.salary_min = parseInt(data.salary_min);
    if (data.salary_max) jobForm.salary_max = parseInt(data.salary_max);

    if (data.qualifications && Array.isArray(data.qualifications)) {
        qualifications.value = data.qualifications.filter(q => q.trim());
    }
}

// Watch for description changes to sync with hidden input
watch(description, (newValue) => {
    jobForm.description = newValue;
});

// Watch for qualifications changes
watch(qualifications, (newValue) => {
    jobForm.qualifications = newValue;
}, { deep: true });

// Initialize form with props data if available
if (props.data && props.success) {
    populateFormWithAIData(props.data);
}

// Submit main job form
function submitJob() {
    // Ensure all reactive data is synced
    jobForm.description = description.value;
    jobForm.qualifications = qualifications.value.filter(q => q.trim() !== '');

    jobForm.post('/health-jobs/jobs');
}
</script>

<template>
    <Head title="Create Job" />
    <AppLayout>
        <div class="min-h-screen bg-gray-50 py-8 dark:bg-gray-900">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Job Creation Form -->
                <div class="mt-12">
                    <div class="mb-6 border-b border-gray-200 pb-4 dark:border-gray-700">
                        <h4 v-if="user?.facility">{{ user?.facility.name }}</h4>
                        <h4 v-else>No Facility</h4>
                        <p class="mt-4 text-gray-600 dark:text-gray-300">Upload an image to auto-populate the form, or fill it out manually</p>
                    </div>

                    <!-- AI Image Upload Section -->
                    <section class="mb-8 bg-blue-50 dark:bg-blue-900/20 rounded-lg p-6 border border-blue-200 dark:border-blue-700">
                        <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-4">
                            🤖 AI Job Extraction
                        </h3>
                        <p class="text-blue-700 dark:text-blue-300 mb-4">
                            Upload an image of a job posting and let AI extract the details automatically
                        </p>

                        <div class="space-y-4">
                            <!-- File Input -->
                            <div>
                                <input
                                    type="file"
                                    id="ai-image"
                                    @input="uploadForm.image = $event.target.files[0]"
                                    class="w-full px-3 py-2 border border-blue-300 rounded-md shadow-sm file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                    accept="image/*"
                                />
                            </div>

                            <!-- Processing Button -->
                            <button
                                type="button"
                                @click="uploadAndProcess"
                                :disabled="!uploadForm.image || isProcessing"
                                class="bg-blue-600 hover:bg-blue-700 disabled:bg-blue-300 text-white font-medium py-2 px-6 rounded-md transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                            >
                                <span v-if="isProcessing" class="flex items-center">
                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Processing with AI...
                                </span>
                                <span v-else>Extract Job Details</span>
                            </button>

                            <!-- Progress Bar for Upload -->
                            <div v-if="uploadForm.progress" class="space-y-2">
                                <div class="flex justify-between text-sm text-blue-600 dark:text-blue-400">
                                    <span>Uploading...</span>
                                    <span>{{ uploadForm.progress.percentage }}%</span>
                                </div>
                                <div class="w-full bg-blue-200 rounded-full h-2">
                                    <div
                                        class="bg-blue-600 h-2 rounded-full transition-all duration-300 ease-out"
                                        :style="`width: ${uploadForm.progress.percentage}%`"
                                    ></div>
                                </div>
                            </div>

                            <!-- Success Message with Details -->
                            <div v-if="aiResponse" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div>
                                        <p class="font-semibold">✅ Job details extracted successfully!</p>
                                        <p class="text-sm mt-1">
                                            Found:
                                            <span v-if="aiResponse.title" class="font-medium">{{ aiResponse.title }}</span>
                                            <span v-if="aiResponse.location"> • {{ aiResponse.location }}</span>
                                            <span v-if="qualifications.length > 0"> • {{ qualifications.length }} requirement(s)</span>
                                        </p>
                                        <p class="text-sm text-green-600 mt-1">Review and edit the populated form below before submitting.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Main Job Form -->
                    <form @submit.prevent="submitJob" class="space-y-6 rounded-lg bg-white p-8 shadow-lg dark:bg-gray-800">
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <!-- Job Title -->
                            <div>
                                <label for="title" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Job Title <small class="text-red-700">*</small>
                                </label>
                                <input
                                    type="text"
                                    v-model="jobForm.title"
                                    id="title"
                                    placeholder="e.g. Registered Nurse"
                                    required
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                                />
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Description <small class="text-red-700">*</small>
                            </label>
                            <QuillEditor
                                v-model:content="description"
                                content-type="html"
                                placeholder="Describe the job role and responsibilities"
                                theme="snow"
                                toolbar="full"
                                class="bg-white dark:bg-gray-700 rounded-lg"
                            />
                        </div>

                        <!-- Job Type & Location -->
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="job_type" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Job Type <small class="text-red-700">*</small>
                                </label>
                                <select
                                    v-model="jobForm.job_type"
                                    id="job_type"
                                    required
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-blue-400 dark:focus:ring-blue-400"
                                >
                                    <option value="" disabled>Select Job Type</option>
                                    <option value="full-time">Full-Time</option>
                                    <option value="part-time">Locum</option>
                                </select>
                            </div>

                            <div v-if="!user?.facility">
                                <label for="location" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Location <small class="text-red-700">*</small>
                                </label>
                                <select
                                    v-model="jobForm.location"
                                    id="location"
                                    required
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-blue-400 dark:focus:ring-blue-400"
                                >
                                    <option value="" disabled>Select location</option>
                                    <option v-for="county in sortedCounties" :key="county.code" :value="county.name">
                                        {{ county.name }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <!-- Salary Range -->
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="salary_min" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Min Range (<i>Optional</i>)
                                </label>
                                <input
                                    type="number"
                                    v-model="jobForm.salary_min"
                                    id="salary_min"
                                    placeholder="e.g. 35000"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                                />
                            </div>
                            <div>
                                <label for="salary_max" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Max Range (<i>Optional</i>)
                                </label>
                                <input
                                    type="number"
                                    v-model="jobForm.salary_max"
                                    id="salary_max"
                                    placeholder="e.g. 85000"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:ring-blue-400 dark:focus:ring-blue-400"
                                />
                            </div>
                        </div>

                        <!-- Dynamic Qualifications Section -->
                        <div>
                            <div class="mb-3 flex items-center justify-between">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Qualifications & Requirements (<i>Optional</i>)
                                </label>
                                <button
                                    type="button"
                                    @click="addQualification"
                                    :disabled="!canAddMore"
                                    class="inline-flex items-center rounded-md border border-transparent bg-blue-100 px-3 py-2 text-sm leading-4 font-medium text-blue-700 hover:bg-blue-200 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-none disabled:cursor-not-allowed disabled:opacity-50 dark:bg-blue-900 dark:text-blue-400 dark:hover:bg-blue-800"
                                >
                                    <svg class="mr-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Add Qualification
                                </button>
                            </div>

                            <div class="space-y-3" v-if="qualifications.length > 0">
                                <div v-for="(qualification, index) in qualifications" :key="index" class="flex items-center gap-3">
                                    <div class="flex-1">
                                        <input
                                            v-model="qualifications[index]"
                                            type="text"
                                            :placeholder="`Qualification ${index + 1} (e.g., Bachelor's degree in Nursing)`"
                                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                                        />
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <button
                                            type="button"
                                            @click="addQualification"
                                            :disabled="!canAddMore"
                                            class="flex-shrink-0 rounded-lg p-1.5 text-blue-600 transition-colors hover:bg-blue-50 hover:text-blue-800 disabled:cursor-not-allowed disabled:opacity-50 dark:text-blue-400 dark:hover:bg-blue-900 dark:hover:text-blue-300"
                                            title="Add qualification"
                                        >
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                        </button>
                                        <button
                                            type="button"
                                            @click="removeQualification(index)"
                                            class="flex-shrink-0 rounded-lg p-1.5 text-red-600 transition-colors hover:bg-red-50 hover:text-red-800 dark:text-red-400 dark:hover:bg-red-900 dark:hover:text-red-300"
                                            title="Remove qualification"
                                        >
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                Add up to {{ maxQualifications }} qualifications. These will appear as bullet points in the job listing.
                            </p>
                        </div>

                        <!-- Active Checkbox -->
                        <div class="flex items-center space-x-3 pt-2">
                            <input
                                type="checkbox"
                                v-model="jobForm.is_active"
                                id="is_active"
                                class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:focus:ring-blue-400"
                            />
                            <label for="is_active" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                Job is Active
                            </label>
                        </div>

                        <!-- Submit -->
                        <div class="pt-4">
                            <button
                                type="submit"
                                :disabled="jobForm.processing"
                                class="flex w-full items-center justify-center rounded-lg bg-blue-600 px-6 py-3.5 text-lg font-semibold text-white transition hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-none disabled:opacity-50 dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:ring-blue-400 dark:focus:ring-offset-gray-800"
                            >
                                <span v-if="jobForm.processing">Creating Job...</span>
                                <span v-else>Create Job</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
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
</style>
