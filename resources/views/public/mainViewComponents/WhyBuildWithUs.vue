<template>
    <div class="md:px-20 px-10 md:-mt-[14rem] -mt-[8rem] mb-24">
        <!-- Header -->
        <div class="w-3/12 md:w-4/12">
            <div class="w-full mt-16 mb-8">
                <h1 class="text-3xl sm:text-4xl md:text-6xl text-zinc-900">
                    How we make it easier
                </h1>
                <div class="w-44 h-1.5 mt-4 bg-orange-500 rounded-full"></div>
            </div>
        </div>

        <!-- Features Grid -->
        <div class="mt-24 grid grid-cols-1 md:grid-cols-2 gap-x-16 gap-y-24">
            <div v-for="(feature, index) in features"
                 :key="index"
                 class="group relative bg-white rounded-2xl p-8 border border-zinc-100 transition-all duration-300 hover:shadow-xl"
                 :class="index === features.length - 1 && features.length % 2 !== 0 ? 'md:col-span-2 md:w-[calc(50%-2rem)] md:mx-auto' : ''"
            >
                <!-- Icon Space -->
                <div class="absolute -top-8 left-8">
                    <div class="w-16 h-16 bg-white rounded-xl border border-zinc-100 flex items-center justify-center shadow-sm">
                        <!-- Replace with your Flaticon icon -->
<!--                        <img src="/path-to-your-icon.svg" alt="Icon" class="w-8 h-8" />-->
                    </div>
                </div>

                <!-- Content -->
                <div class="mt-8">
                    <h3 class="text-xl font-semibold text-zinc-900 mb-4">
                        {{ feature.title }}
                    </h3>

                    <p class="text-zinc-600 leading-relaxed">
                        {{ feature.description }}
                    </p>
                </div>

                <!-- Thinner Orange Accent -->
                <div class="absolute top-0 left-0 w-1 h-12 bg-orange-500 rounded-full rounded-br-full"></div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            features: [
                {
                    title: "Streamlined Resume Template",
                    description: "Our carefully designed template provides a professional structure that effectively showcases your experience and skills, making it easier to present your qualifications to potential employers.",
                    icon: "path-to-template-icon.svg"
                },
                {
                    title: "Easy Updates and Maintenance",
                    description: "Keep your resume current with our user-friendly editing system. Make updates directly on the platform, ensuring your professional profile stays current without the hassle of managing multiple files.",
                    icon: "path-to-update-icon.svg"
                },
                {
                    title: "Specialized Fields Classification",
                    description: "Define your expertise with precision through our dual specialization system. Select two primary fields of focus, each with detailed sub-specializations, helping employers better understand your specific skill sets and professional direction.",
                    icon: "path-to-classification-icon.svg"
                },
                {
                    title: "Efficient Information Management",
                    description: "Our structured sections guide you through filling out your professional information in an organized way, helping you build a comprehensive profile that highlights your key qualifications.",
                    icon: "path-to-management-icon.svg"
                },
                {
                    title: "Straightforward Resume Building",
                    description: "Create your professional resume with our intuitive interface. Our clearly labeled sections and guided input fields help you compile your experience and skills effectively.",
                    icon: "path-to-building-icon.svg"
                },
                {
                    title: "Secure Profile Management",
                    description: "Your professional information is safely stored in one place, making it simple to access and update your resume whenever needed.",
                    icon: "path-to-secure-icon.svg"
                },
                {
                    title: "Clean PDF Generation",
                    description: "Export your resume as a professionally formatted PDF document. The generated file maintains our clean, structured layout that presents your information in a clear and readable format.",
                    icon: "path-to-pdf-icon.svg"
                }
            ]
        }
    }
}
</script>
