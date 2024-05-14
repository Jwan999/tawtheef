<template>
    <div class="">
        <TableComponent title="Applicants" :tableHeaders="tableHeaders" :tableData="applicants"/>
    </div>
</template>

<script setup>
import TableComponent from "../../js/components/TableComponent.vue";
import {onMounted, ref} from "vue";

const tableHeaders = ref(['Full name', 'Job status', 'Field of interest', 'Phone number', 'Email Address'])
const applicants = ref([])

const getApplicants = async () => {
    try {
        const response = await axios.get('/api/applicants');
        console.log('this is the applicants'+response.data)
        return response.data;
    } catch (error) {
        console.error('Error fetching applicants:', error);
        throw error;
    }
};

onMounted(async () => {
    try {
        applicants.value = await getApplicants();
        // Do something with the fetched applicantsData, such as updating a reactive variable
    } catch (error) {
        // Handle the error if necessary
    }
});
</script>


<style scoped>

</style>
