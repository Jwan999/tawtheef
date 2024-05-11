<template>
    <PolarArea :data="chartData" :options="chartOptions"/>
</template>

<script setup>
import {
    Chart as ChartJS,
    RadialLinearScale,
    ArcElement,
    Tooltip,
} from 'chart.js'
import {PolarArea} from 'vue-chartjs'
import {ref} from "vue";

ChartJS.register(RadialLinearScale, ArcElement, Tooltip)


const chartData = ref({
    labels: ['Cities'],
    datasets: [
        {
            label: ['Baghdad', 'Basra', 'Mosul', 'Erbil', 'Kirkuk', 'Najaf', 'Karbala', 'Nasiriyah', 'Sulaymaniyah', 'Ramadi', 'Fallujah', 'Diyala', 'Hilla', 'Tikrit', 'Samarra', 'Kut', 'Amara', 'Kufa'],
            backgroundColor: '#E26600',
            data: [100, 74, 44, 40, 30, 10, 99, 30, 10, 67, 66, 18, 15, 35, 18, 5, 20]
        }
    ]
})
const chartOptions = ref({responsive: true})


</script>
