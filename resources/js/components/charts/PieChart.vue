<template>
    <Pie :data="chartData" :options="chartOptions" />
</template>

<script setup>
import { Chart as ChartJS, ArcElement, Tooltip, Legend } from 'chart.js'
import { Pie } from 'vue-chartjs'
import {ref} from "vue";

ChartJS.register(ArcElement, Tooltip, Legend)

const chartData = ref({
    labels: ['Female', 'Male'],
    datasets: [
        {
            backgroundColor: ['#E26600', '#3F3F46'],
            data: [300, 366]
        }
    ]
})
const chartOptions = ref({
    responsive: true,
    maintainAspectRatio: false
})

</script>
