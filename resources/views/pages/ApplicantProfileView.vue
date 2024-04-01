<template>
    <div class="grid grid-cols-12 gap-6 p-4 sm:ml-64 mt-16">
        <!--first row-->
        <ApplicantDetailsComponent></ApplicantDetailsComponent>

        <div class="col-span-3 space-y-6">
            <ContactComponent editMode></ContactComponent>
            <EducationComponent editMode></EducationComponent>
            <LanguagesComponent editMode></LanguagesComponent>
            <SkillsComponent editMode></SkillsComponent>
            <ToolsComponent editMode></ToolsComponent>
        </div>

        <div class="col-span-9 space-y-8">
            <!--summary section-->
            <SummaryComponent editMode></SummaryComponent>
            <!--employment section-->
            <EmploymentComponent editMode></EmploymentComponent>
            <!--courses section-->
            <CoursesComponent editMode></CoursesComponent>
            <!--activities section-->
            <ActivitiesComponent editMode></ActivitiesComponent>
        </div>


    </div>
</template>

<script>
import ApplicantDetailsComponent from "../../js/components/applicantProfileComponents/ApplicantDetailsComponent.vue";
import ContactComponent from "../../js/components/applicantProfileComponents/ContactComponent.vue";
import EducationComponent from "../../js/components/applicantProfileComponents/EducationComponent.vue";
import LanguagesComponent from "../../js/components/applicantProfileComponents/LanguagesComponent.vue";
import SkillsComponent from "../../js/components/applicantProfileComponents/SkillsComponent.vue";
import ToolsComponent from "../../js/components/applicantProfileComponents/ToolsComponent.vue";
import SummaryComponent from "../../js/components/applicantProfileComponents/SummaryComponent.vue";
import EmploymentComponent from "../../js/components/applicantProfileComponents/EmploymentComponent.vue";
import CoursesComponent from "../../js/components/applicantProfileComponents/CoursesComponent.vue";
import ActivitiesComponent from "../../js/components/applicantProfileComponents/ActivitiesComponent.vue";

export default {
    name: "ApplicantProfileView",
    data() {
        return {
            editMode: true
        }
    },
    components: {
        ActivitiesComponent,
        CoursesComponent,
        EmploymentComponent,
        SummaryComponent,
        ToolsComponent,
        SkillsComponent, LanguagesComponent, EducationComponent, ContactComponent, ApplicantDetailsComponent
    },


}
</script>
