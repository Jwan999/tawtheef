<template>

    <Bar

        :options="chartOptions"
        :data="chartData"
    />

</template>

<script setup>
import {Bar} from 'vue-chartjs'
import {Chart as ChartJS, BarElement, LinearScale, CategoryScale, Tooltip} from 'chart.js'

import {ref} from "vue";
// Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale
ChartJS.register(BarElement, LinearScale, CategoryScale, Tooltip)

const chartData = ref({
    labels: ['Applicants', 'Published Resumes'],
    datasets: [{
        label: 'Applicants',
        data: [645, 566, 73],
        backgroundColor: '#E26600',
    }]
})
const chartOptions = ref({
    responsive: true
})
</script>

<style scoped>

</style>
