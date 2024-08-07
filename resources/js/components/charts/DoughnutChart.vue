<template>
    <Doughnut :data="chartData" :options="chartOptions"/>
</template>

<script setup>
import {Chart as ChartJS, ArcElement, Tooltip, Legend} from 'chart.js'
import {Doughnut} from 'vue-chartjs'
import {ref} from "vue";

ChartJS.register(ArcElement, Tooltip, Legend)


const chartData = ref({
    labels: ['Available for Work', 'Not Available'],
    datasets: [
        {
            backgroundColor: ['#E26600', '#3F3F46'],
            data: [556, 146]
        }
    ]
})
const chartOptions = ref({
    responsive: true,
    maintainAspectRatio: false
})
</script>
