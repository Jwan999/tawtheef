<!-- Main.vue -->
<template>
    <div>
        <div class="">
            <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700">
                <div class="grid grid-cols-3 gap-4 mb-4 h-[300px]">
                    <div class="flex items-center p-4 rounded bg-gray-50 dark:bg-gray-800 h-[300px]">
                        <BarChart></BarChart>
                    </div>
                    <div class="flex items-center p-4 rounded bg-gray-50 dark:bg-gray-800 h-[300px]">
                       <DoughnutChart></DoughnutChart>
                    </div>
                    <div class="flex items-center p-4 rounded bg-gray-50 dark:bg-gray-800 h-[300px]">
                        <PieChart></PieChart>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-4 h-[450px]">
                    <div class="col-span-2 flex justify-center items-center p-4 mb-4 rounded bg-gray-50 dark:bg-gray-800 h-[450px] w-[900px]">
                        <LineChart></LineChart>

                    </div>
                    <div class="col-span-1 p-4 mb-4 rounded bg-gray-50 dark:bg-gray-800 h-[450px] w-auto">
                        <PolarChart></PolarChart>

                    </div>

                </div>


            </div>
        </div>
    </div>
</template>
<script setup>


import BarChart from "../../js/components/charts/BarChart.vue";
import LineChart from "../../js/components/charts/LineChart.vue";
import PieChart from "../../js/components/charts/PieChart.vue";
import PolarChart from "../../js/components/charts/PolarChart.vue";
import DoughnutChart from "../../js/components/charts/DoughnutChart.vue";
</script>

<style scoped>

</style>
