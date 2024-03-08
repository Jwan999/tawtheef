<template>
    <div class="p-4 sm:ml-64 mt-16">
        <TableComponent title="Applicants" :tableHeaders="tableHeaders" :tableData="applicants"/>
    </div>
</template>

<script>
import TableComponent from "../../js/components/TableComponent.vue";

export default {
    name: "ApplicantsView",
    components: {TableComponent},
    data() {
        return {
            tableHeaders: ['Full name', 'Job status', 'Field of interest', 'Phone number', 'Email Address'],
            applicants: [{
                fullName: 'name',
                jobStatus: 'Job statue',
                field: 'Field',
                phone: 'Phone',
                email: 'Email',
            }],
        }
    },
}
</script>

<style scoped>

</style>
