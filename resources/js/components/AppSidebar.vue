<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { BookOpen, Folder, LayoutGrid, Users, Settings, FileText } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';
import { computed, onMounted } from 'vue';

const page = usePage();

// Debug function
const debugLog = (message: string, data?: any) => {
    // console.log(`[SIDEBAR DEBUG] ${message}`, data);
};

// Enhanced debug logging on mount
// onMounted(() => {
//     const user = page.props.auth?.user;
//     debugLog('=== SIDEBAR DEBUG INFO ===');
//     debugLog('Full user object:', user);
//     debugLog('User permissions raw:', user?.permissions);
//     debugLog('User permissions type:', typeof user?.permissions);
//     debugLog('User roles raw:', user?.roles);
//     debugLog('User roles type:', typeof user?.roles);
//
//     // Test the hasRole function specifically for super-admin
//     debugLog('Testing hasRole("super-admin"):', hasRole('super-admin'));
//
//     // Check if user has any roles at all
//     if (user?.roles) {
//         debugLog('User has roles property');
//         if (Array.isArray(user.roles)) {
//             debugLog('Roles is array with length:', user.roles.length);
//             debugLog('All roles:', user.roles);
//         } else {
//             debugLog('Roles is not an array, trying to parse...');
//         }
//     } else {
//         debugLog('User has NO roles property');
//     }
// });

// Helper functions with enhanced logging
const hasPermission = (permission: string): boolean => {
    const permissions = page.props.auth.user?.permissions;
    debugLog(`Checking permission: "${permission}"`, { permissions });

    if (!permissions || !Array.isArray(permissions)) {
        debugLog(`No permissions or not array`);
        return false;
    }

    // Handle array of permission objects
    const result = permissions.some((p: any) =>
        (typeof p === 'string' && p === permission) ||
        (typeof p === 'object' && p.name === permission)
    );

    debugLog(`Permission check result for "${permission}":`, result);
    return result;
};

const hasRole = (role: string): boolean => {
    const user = page.props.auth?.user;
    const roles = user?.roles;

    debugLog(`Checking role: "${role}"`, { roles });

    if (!roles || !Array.isArray(roles)) {
        debugLog(`No roles found or not array for user when checking: "${role}"`);
        return false;
    }

    // Handle array of role objects with 'name' property
    const result = roles.some((r: any) =>
        (typeof r === 'string' && r === role) ||
        (typeof r === 'object' && r.name === role)
    );

    debugLog(`Role check result for "${role}":`, result);
    debugLog(`Available role names:`, roles.map((r: any) => typeof r === 'object' ? r.name : r));
    return result;
};

const hasAnyRole = (rolesToCheck: string[]): boolean => {
    const userRoles = page.props.auth.user?.roles;
    debugLog(`Checking any role from: [${rolesToCheck.join(', ')}]`, { userRoles });

    if (!Array.isArray(userRoles)) {
        debugLog('User roles is not an array when checking hasAnyRole');
        return false;
    }

    const result = rolesToCheck.some((role) =>
        userRoles.some((r: any) =>
            (typeof r === 'string' && r === role) ||
            (typeof r === 'object' && r.name === role)
        )
    );

    debugLog(`hasAnyRole result:`, result);
    return result;
};

const hasAnyPermission = (permissionsToCheck: string[]): boolean => {
    const userPermissions = page.props.auth.user?.permissions;
    debugLog(`Checking any permission from: [${permissionsToCheck.join(', ')}]`, { userPermissions });

    if (!Array.isArray(userPermissions)) {
        debugLog('User permissions is not an array when checking hasAnyPermission');
        return false;
    }

    const result = permissionsToCheck.some((permission) =>
        userPermissions.some((p: any) =>
            (typeof p === 'string' && p === permission) ||
            (typeof p === 'object' && p.name === permission)
        )
    );

    debugLog(`hasAnyPermission result:`, result);
    return result;
};

// Define all possible nav items with their required permissions/roles
const allNavItems: (NavItem & {
    requiredPermissions?: string[];
    requiredRoles?: string[];
    requireAnyPermission?: boolean;
    requireAnyRole?: boolean;
})[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
        icon: LayoutGrid,
        // No requirements - always visible
    },
    {
        title: 'Jobs',
        href: '/health-jobs',
        icon: LayoutGrid,
        requiredRoles: ['super-admin'],
    },
    {
        title: 'Users Management',
        href: '/users',
        icon: Users,
        requiredRoles: ['admin', 'super-admin'],
        requireAnyRole: true,
    },
    {
        title: 'Reports',
        href: '/reports',
        icon: FileText,
        requiredPermissions: ['view reports', 'generate reports'],
        requireAnyPermission: false,
    },
    {
        title: 'Settings',
        href: '/settings',
        icon: Settings,
        requiredRoles: ['admin'],
        requiredPermissions: ['manage settings'],
    },
];

// Enhanced filter with detailed logging
const mainNavItems = computed((): NavItem[] => {
    debugLog('=== FILTERING NAV ITEMS ===');

    const filteredItems = allNavItems.filter((item) => {
        debugLog(`\n--- Checking item: "${item.title}" ---`);
        debugLog('Item requirements:', {
            requiredPermissions: item.requiredPermissions,
            requiredRoles: item.requiredRoles,
            requireAnyPermission: item.requireAnyPermission,
            requireAnyRole: item.requireAnyRole
        });

        // If no requirements, always show
        if (!item.requiredPermissions && !item.requiredRoles) {
            debugLog(`"${item.title}" has no requirements - showing`);
            return true;
        }

        let hasRequiredPermissions = true;
        let hasRequiredRoles = true;

        // Check permissions
        if (item.requiredPermissions) {
            debugLog(`Checking permissions for "${item.title}"`);
            if (item.requireAnyPermission) {
                hasRequiredPermissions = hasAnyPermission(item.requiredPermissions);
                debugLog(`Any permission check result:`, hasRequiredPermissions);
            } else {
                hasRequiredPermissions = item.requiredPermissions.every((permission) => {
                    const result = hasPermission(permission);
                    debugLog(`Permission "${permission}" check:`, result);
                    return result;
                });
                debugLog(`All permissions check result:`, hasRequiredPermissions);
            }
        }

        // Check roles
        if (item.requiredRoles) {
            debugLog(`Checking roles for "${item.title}"`);
            if (item.requireAnyRole) {
                hasRequiredRoles = hasAnyRole(item.requiredRoles);
                debugLog(`Any role check result:`, hasRequiredRoles);
            } else {
                hasRequiredRoles = item.requiredRoles.every((role) => {
                    const result = hasRole(role);
                    debugLog(`Role "${role}" check:`, result);
                    return result;
                });
                debugLog(`All roles check result:`, hasRequiredRoles);
            }
        }

        const finalResult = hasRequiredPermissions && hasRequiredRoles;
        debugLog(`Final result for "${item.title}":`, {
            hasRequiredPermissions,
            hasRequiredRoles,
            finalResult
        });

        return finalResult;
    });

    debugLog('=== FINAL FILTERED ITEMS ===');
    debugLog('Visible items:', filteredItems.map(item => item.title));

    return filteredItems;
});

const footerNavItems: NavItem[] = [
    {
        title: 'Github Repo',
        href: 'https://github.com/laravel/vue-starter-kit',
        icon: Folder,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits#vue',
        icon: BookOpen,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('dashboard')">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
