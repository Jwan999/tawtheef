<!-- views/dashboard/DashboardView.vue -->
<template>
    <div class="min-h-screen bg-gray-100"> <!-- Single root element -->
        <DashboardNavigation />
        <div class="p-4 sm:ml-64">
            <div class="p-4 mt-14">
                <router-view></router-view>
            </div>
        </div>
    </div>
</template>

<script setup>
import DashboardNavigation from '../../js/components/NavigationComponent.vue'
</script>
