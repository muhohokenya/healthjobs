<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { BookOpen, Folder, LayoutGrid, Users, Settings, FileText, Cog, User } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';
import { computed, onMounted } from 'vue';
import { useAuth } from '@/utils/auth';

const user = useAuth();

const allNavItems = [
    {
        title: 'Dashboard',
        href: '/dashboard',
        icon: LayoutGrid,
        // No requirements - always visible
    },
    {
        title: 'Jobs',
        href: '/health-jobs',
        icon: Users,
        requiredPermissions: ['view-job-postings','create-job-postings'],
    },

    {
        title: 'Access Management',
        href: '/iam',
        icon: Cog,
        requiredRoles: ['super-admin'],
    },
    {
        title: 'roles',
        href: '/iam/roles',
        icon: User,
        requiredRoles: ['super-admin'],
    },
    {
        title: 'Reports',
        href: '/reports',
        icon: FileText,
        requiredPermissions: ['view reports', 'generate reports'],
        requireAnyPermission: false, // requires ALL permissions
    },
    {
        title: 'Settings',
        href: '/settings',
        icon: Settings,
        requiredRoles: ['admin'],
        requiredPermissions: ['manage settings'],
    },
];

// Filter items based on access
const mainNavItems = computed((): NavItem[] => {
    return allNavItems.filter((item) => {
        // Extract access requirements from the item
        const requirements = {
            requiredRoles: item.requiredRoles || [],
            requiredPermissions: item.requiredPermissions || [],
            requireAnyRole: item.requireAnyRole || false,
            requireAnyPermission: item.requireAnyPermission || false,
        };

        return user.canAccess(requirements);
    });
});

const footerNavItems: NavItem[] = [];
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
