<template>

        <Line :data="chartData" :options="chartOptions"/>


</template>

<script setup>
import {
    Chart as ChartJS,
    CategoryScale,
    Tooltip,
    LinearScale,
    PointElement,
    LineElement,
} from 'chart.js'
import {Line} from 'vue-chartjs'
import {ref} from "vue";


ChartJS.register(
    CategoryScale,
    Tooltip,
    LinearScale,
    PointElement,
    LineElement,
)

const chartData = ref({
    labels: ['Creative & Design', 'Development', 'Business & Management', 'Writing & Editing', 'Science & Engineering', 'Other'],
    datasets: [
        {
            label: 'Specialities',
            backgroundColor: '#E26600',
            data: [98, 123, 177, 57, 94, 66]
        }
    ]
})
const chartOptions = ref({ responsive: true})


</script>
