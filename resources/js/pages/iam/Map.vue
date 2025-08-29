
<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Form, Head } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    roles: Array, // each role => {id, name, permissions: []}
    permissions: Array, // all permissions
});

const selectedRole = ref<number | null>(null);
const selectedPermission = ref<number | null>(null);

const formatRoleName = (name: string): string => {
    if (!name) {
        return '';
    }
    return name
        .replace(/[_-]+/g, ' ')
        .trim()
        .split(' ')
        .filter(Boolean)
        .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
        .join(' ');
};

const getRolePermissions = (roleId: number) => {
    const role = props.roles.find((r: any) => r.id === roleId);
    return role ? role.permissions : [];
};

const availablePermissions = (roleId: number) => {
    const assigned = getRolePermissions(roleId).map((p: any) => p.id);
    return props.permissions.filter((p: any) => !assigned.includes(p.id));
};

const addPermission = (roleId: number, permissionId: number) => {
    if (!roleId || !permissionId) {
        return;
    }
    const role = props.roles.find((r: any) => r.id === roleId);
    if (role) {
        const perm = props.permissions.find((p: any) => p.id === permissionId);
        if (perm && !role.permissions.some((p: any) => p.id === perm.id)) {
            role.permissions.push(perm);
        }
    }
    selectedPermission.value = null;
};

const removePermission = (roleId: number, permissionId: number) => {
    const role = props.roles.find((r: any) => r.id === roleId);
    if (role) {
        role.permissions = role.permissions.filter((p: any) => p.id !== permissionId);
    }
};
</script>

<template>
    <AppLayout>
        <Head title="Role & Permission Management" />

        <div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 px-6 py-12 lg:px-8">
            <div class="mx-auto max-w-6xl overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-xl">
                <!-- Header -->
                <div class="border-b border-gray-200 py-6 text-center">
                    <h2 class="text-2xl font-bold text-gray-900">Role & Permission Management</h2>
                    <p class="text-gray-600">Manage permissions assigned to each role</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3">
                    <!-- Left: Roles -->
                    <div class="max-h-[600px] overflow-y-auto border-r border-gray-200 bg-gray-50 p-4 md:p-6">
                        <h3 class="mb-4 text-lg font-semibold text-gray-800">Roles</h3>
                        <ul class="space-y-2">
                            <li
                                v-for="role in props.roles"
                                :key="role.id"
                                @click="selectedRole = role.id"
                                class="cursor-pointer rounded-lg px-4 py-2 text-sm font-medium"
                                :class="[
                                    selectedRole === role.id
                                        ? 'bg-blue-600 text-white'
                                        : 'border border-gray-200 bg-white text-gray-700 hover:bg-gray-100',
                                ]"
                            >
                                {{ formatRoleName(role.name) }}
                            </li>
                        </ul>
                    </div>

                    <!-- Right: Permissions -->
                    <div class="col-span-2 max-h-[600px] overflow-y-auto p-6">
                        <div v-if="selectedRole" class="space-y-6">
                            <Form :action="route('iam.roles.update')" method="post" class="space-y-6">
                                <!-- Current Permissions -->
                                <div>
                                    <h3 class="mb-2 font-bold text-gray-800">Current Permissions</h3>
                                    <div class="flex flex-wrap gap-2">
                                        <span
                                            v-for="perm in getRolePermissions(selectedRole)"
                                            :key="perm.id"
                                            class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-sm font-medium text-blue-700"
                                        >
                                            {{ perm.name }}
                                            <button
                                                type="button"
                                                @click="removePermission(selectedRole, perm.id)"
                                                class="ml-2 text-red-500 hover:text-red-700"
                                            >
                                                ✕
                                            </button>
                                            <!-- Hidden inputs: one for each permission -->
                                            <input type="hidden" name="permissions[]" :value="perm.id" />
                                        </span>
                                        <span v-if="!getRolePermissions(selectedRole).length" class="text-sm text-gray-400">
                                            No permissions assigned
                                        </span>
                                    </div>
                                </div>

                                <!-- Add Permission -->
                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-gray-700">Add Permission</label>
                                    <div class="flex gap-2">
                                        <select
                                            v-model="selectedPermission"
                                            class="flex-1 rounded-xl border bg-gray-50 px-4 py-3 focus:ring-2 focus:ring-blue-500"
                                        >
                                            <option disabled value="">-- Select Permission --</option>
                                            <option v-for="perm in availablePermissions(selectedRole)" :key="perm.id" :value="perm.id">
                                                {{ perm.name }}
                                            </option>
                                        </select>
                                        <button
                                            type="button"
                                            @click="addPermission(selectedRole, selectedPermission)"
                                            :disabled="!selectedPermission"
                                            class="rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700 disabled:bg-gray-400"
                                        >
                                            Add
                                        </button>
                                    </div>
                                </div>

                                <!-- Submit -->
                                <div class="pt-4">
                                    <input type="hidden" name="role_id" :value="selectedRole" />
                                    <button
                                        type="submit"
                                        class="w-full rounded-xl bg-green-600 px-6 py-3 font-semibold text-white shadow-lg transition-all hover:bg-green-700"
                                    >
                                        Save Changes
                                    </button>
                                </div>
                            </Form>
                        </div>

                        <div v-else class="flex h-full items-center justify-center text-gray-500">
                            <p>Select a role to manage its permissions</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
