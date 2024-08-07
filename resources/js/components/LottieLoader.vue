<template>
    <div class="flex justify-center items-center h-screen">
        <dotlottie-player
            src="https://lottie.host/30506056-41e7-487d-8cd8-29c774ae1287/DOiYhZ9jIM.json"
            background="transparent"
            speed="1"
            style="width: 300px; height: 300px;"
            loop
            autoplay
        ></dotlottie-player>
    </div>
</template>

<script setup>
import { onMounted } from 'vue';

onMounted(() => {
    import('https://unpkg.com/@dotlottie/player-component@latest/dist/dotlottie-player.mjs');
});
</script>
