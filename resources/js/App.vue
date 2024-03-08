<template>
    <div>
        <NavigationComponent/>
        <router-view></router-view>
    </div>
</template>

<script>
import NavigationComponent from './components/NavigationComponent.vue'

export default {
    components: {NavigationComponent},

    name: "App.vue"
}
</script>

<style scoped>

</style>
