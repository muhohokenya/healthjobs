import { usePage } from '@inertiajs/vue3'

export const useAuth = () => {
    const page = usePage()
    const user = page.props.auth.user

    // Check if user has a specific roles
    const hasRole = (role:string) => {
        if (!user?.roles || !Array.isArray(user.roles)) return false

        return user.roles.some(r =>
            (typeof r === 'string' && r === role) ||
            (typeof r === 'object' && r.name === role)
        )
    }

    // Check if user has a specific permission
    const hasPermission = (permission:string) => {
        if (!user?.permissions || !Array.isArray(user.permissions)) return false

        return user.permissions.some(p =>
            (typeof p === 'string' && p === permission) ||
            (typeof p === 'object' && p.name === permission)
        )
    }

    // Check if user has ANY of the given roles
    const hasAnyRole = (roles:string) => {
        if (!user?.roles || !Array.isArray(user.roles)) return false

        return roles.some(role =>
            user.roles.some(r =>
                (typeof r === 'string' && r === role) ||
                (typeof r === 'object' && r.name === role)
            )
        )
    }

    // Check if user has ALL of the given roles
    const hasAllRoles = (roles) => {
        if (!user?.roles || !Array.isArray(user.roles)) return false

        return roles.every(role =>
            user.roles.some(r =>
                (typeof r === 'string' && r === role) ||
                (typeof r === 'object' && r.name === role)
            )
        )
    }

    // Check if user has ANY of the given permissions
    const hasAnyPermission = (permissions:string) => {
        if (!user?.permissions || !Array.isArray(user.permissions)) return false

        return permissions.some(permission =>
            user.permissions.some(p =>
                (typeof p === 'string' && p === permission) ||
                (typeof p === 'object' && p.name === permission)
            )
        )
    }

    // Check if user has ALL of the given permissions
    const hasAllPermissions = (permissions:string) => {
        if (!user?.permissions || !Array.isArray(user.permissions)) return false

        return permissions.every(permission =>
            user.permissions.some(p =>
                (typeof p === 'string' && p === permission) ||
                (typeof p === 'object' && p.name === permission)
            )
        )
    }

    // Main function to check access based on your requirements format
    const canAccess = (requirements = {}) => {
        const {
            requiredRoles = [],
            requiredPermissions = [],
            requireAnyRole = false,
            requireAnyPermission = false
        } = requirements

        let hasRequiredRoles = true
        let hasRequiredPermissions = true

        // Check roles
        if (requiredRoles.length > 0) {
            if (requireAnyRole) {
                hasRequiredRoles = hasAnyRole(requiredRoles)
            } else {
                hasRequiredRoles = hasAllRoles(requiredRoles)
            }
        }

        // Check permissions
        if (requiredPermissions.length > 0) {
            if (requireAnyPermission) {
                hasRequiredPermissions = hasAnyPermission(requiredPermissions)
            } else {
                hasRequiredPermissions = hasAllPermissions(requiredPermissions)
            }
        }

        return hasRequiredRoles && hasRequiredPermissions
    }

    return {
        // User data
        ...user,

        // Individual check methods
        hasRole,
        hasPermission,
        hasAnyRole,
        hasAllRoles,
        hasAnyPermission,
        hasAllPermissions,

        // Main access check method
        canAccess
    }
}
