<template>
    <div class="">
        <TableComponent title="Companies" :tableHeaders="tableHeaders" :tableData="companies"/>
    </div>
</template>

<script>
import TableComponent from "../../js/components/TableComponent.vue";

export default {
    name: "CompaniesView",
    components: {TableComponent},
    data() {
        return {
            tableHeaders: ['Company', 'Employment status', 'Phone number', 'Email Address'],
            companies: [{
                id: 1,
                company: 'Company name',
                employmentStatus: 'Employment',
                phone: 'Phone',
                email: 'Email',
            }],
        }
    },
}
</script>

<style scoped>

</style>
