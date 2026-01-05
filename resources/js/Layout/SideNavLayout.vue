<template>
    <div>
        <nav id="topNav" class="navbar fixed-top" :class="isNavOpen ? 'top-navbar' : 'top-navbar-expand'">
            <div class="container-fluid">
                <div class="d-flex align-items-center">
                    <a id="MenuBar" @click="NavOpenClose" class="icon-nav mx-2 my-1 h5">
                        <i class="fa text-white fa-bars"></i>
                    </a>
                    <span class="navbar-brand font-lobster text-white ms-2 d-none d-md-block">
                        Admin Panel
                    </span>
                </div>
                <div>
                    <span class="user-greeting me-3">
                        <i class="fa fa-user me-1"></i>
                        Welcome, {{ user?.name || 'User' }}
                    </span>
                    <Link :href="route('logout')" class="btn-logout">
                        <i class="fa fa-sign-out-alt me-1"></i> Logout
                    </Link>
                </div>
            </div>
        </nav>
        <div id="sideNav" :class="isNavOpen ? 'side-nav-open' : 'side-nav-close'">
            <div class="side-nav-top text-center">
                <h2 class="font-lobster text-white mt-4 mb-2">Admin</h2>
                <p class="sidebar-tagline">Admin Panel</p>
                <div class="sidebar-divider"></div>
            </div>

            <Link :href="route('DashboardPage')" class="side-bar-item" :class="{ active: isActiveRoute('DashboardPage') }" @click="setActiveMenu(route('DashboardPage'))">
                <span class="side-bar-item-icon"><i class="fa fa-tachometer-alt"/></span>
                <span class="side-bar-item-caption">Dashboard</span>
            </Link>
            
            <!-- Role-based Users link -->
            <Link v-if="canViewUsers" :href="route('users.index')" class="side-bar-item" :class="{ active: isActiveRoute('users.index') }" @click="setActiveMenu(route('users.index'))">
                <span class="side-bar-item-icon"><i class="fa fa-users"/></span>
                <span class="side-bar-item-caption">Users</span>
            </Link>

            <!-- Role-based Products link -->
<Link v-if="canViewMembers" :href="route('members.index')" class="side-bar-item" :class="{ active: isActiveRoute('members.index') }" @click="setActiveMenu(route('members.index'))">
    <span class="side-bar-item-icon"><i class="fa fa-user-friends"/></span>
    <span class="side-bar-item-caption">Members</span>
</Link>
            
            <!-- Role & Permission Management (Admin Only) -->
            <div v-if="canViewRoles || canViewPermissions" class="sidebar-divider"></div>
            
            <Link v-if="canViewRoles" :href="route('roles.index')" class="side-bar-item" :class="{ active: isActiveRoute('roles.index') }" @click="setActiveMenu(route('roles.index'))">
                <span class="side-bar-item-icon"><i class="fa fa-user-tag"/></span>
                <span class="side-bar-item-caption">Roles</span>
            </Link>
            
            <Link v-if="canViewPermissions" :href="route('permissions.index')" class="side-bar-item" :class="{ active: isActiveRoute('permissions.index') }" @click="setActiveMenu(route('permissions.index'))">
                <span class="side-bar-item-icon"><i class="fa fa-key"/></span>
                <span class="side-bar-item-caption">Permissions</span>
            </Link>   
                                

        </div>
        <div id="content" :class="isNavOpen ? 'content' : 'content-expand'">
            <div class="container-fluid">
                <main>
                    <slot></slot>
                </main>
            </div>
        </div>
    </div>
</template>


<script setup>
import { ref, watchEffect, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();

// Get user data from Inertia shared props
const user = computed(() => page.props.auth?.user || null);
const userRole = computed(() => user.value?.role || 'user');
const permissions = computed(() => page.props.can || []);
permissions.value.includes('view-member') // FALSE

// Check if user is admin
const isAdmin = computed(() => userRole.value === 'admin');

// Check permissions
const canViewUsers = computed(() => permissions.value.includes('view-user'));
const canViewProducts = computed(() => permissions.value.includes('view-product'));
const canViewMembers = computed(() => permissions.value.includes('view-member'));
const canViewMemershipTier = computed(() => permissions.value.includes('view-membershiptier'));
const canViewRoles = computed(() => permissions.value.includes('view-role'));
const canViewPermissions = computed(() => permissions.value.includes('view-permission'));

// Get current route name
const currentRouteName = computed(() => page.props.ziggy?.location || '');
console.log(currentRouteName.value);

// Active menu handling
const activeMenu = ref(localStorage.getItem('activeMenu') || currentRouteName.value);

// Check if a route is active
const isActiveRoute = (routeName) => {
    const currentRoute = page.props.route || page.url || window.location.pathname;
    const targetRoute = route(routeName);
    
    // Check if the current URL matches the target route URL
    return currentRoute === targetRoute || 
           currentRoute.startsWith(targetRoute) ||
           activeMenu.value === targetRoute;
};

watchEffect(() => {
    localStorage.setItem('activeMenu', activeMenu.value);
});

const setActiveMenu = (routeUrl) => {
    console.log('Setting active menu:', routeUrl);
    activeMenu.value = routeUrl;
};

// Track sidebar state
const isNavOpen = ref(localStorage.getItem('isNavOpen') === 'true');

const NavOpenClose = () => {
    isNavOpen.value = !isNavOpen.value;
    localStorage.setItem('isNavOpen', isNavOpen.value);
};

</script>

<style scoped>
/* Component-specific active state override */
.active {
    background-color: #000000 !important;
    color: white !important;
}

/* Mobile-specific responsive adjustments */
@media (max-width: 768px) {
    .user-greeting {
        display: none; /* Hide on mobile to save space */
    }
    
    .content, .content-expand {
        margin-left: 0;
    }
    
    .side-nav-open {
        left: 0;
        z-index: 1040;
    }
    
    .form-actions {
        flex-direction: column;
        align-items: stretch;
    }
    
    .form-actions .text-end {
        text-align: left !important;
        margin-top: 0.5rem;
    }
}
</style>

