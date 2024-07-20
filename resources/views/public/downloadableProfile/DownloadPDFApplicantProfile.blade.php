<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>John Doe - Resume</title>
    <style>
        html, body {
            font-family: "Dosis", sans-serif;
            font-optical-sizing: auto;
            font-style: normal;
        }
    </style>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 font-sans">
<div class="max-w-5xl mx-auto bg-white shadow-lg">

    <div class="flex">
        <!-- Left Column -->
        <div class="w-1/3 p-6 bg-gray-50">
            <div class="mb-6">
                <img src="https://via.placeholder.com/150" alt="John Doe" class="w-32 h-32 rounded-full mx-auto mb-4">
                <h2 class="text-2xl font-bold text-center" >John Doe</h2>
            </div>

            <section class="mb-6">
                <h3 class="text-lg font-semibold mb-2 text-orange-500">Specializations</h3>
                <div class="bg-orange-500 text-white p-2 mb-2">
                    Development, Creative & Design
                </div>
                <p class="font-semibold">Experienced with the following:</p>
                <ul class="list-disc list-inside">
                    <li>Full-Stack Development</li>
                    <li>User Experience (UX) Design</li>
                    <li>User Interface (UI) Design</li>
                    <li>Mobile App Development</li>
                </ul>
            </section>

            <section class="mb-6">
                <h3 class="text-lg font-semibold mb-2 text-orange-500">Educational Background</h3>
                <p>2015 - 2019</p>
                <p class="font-semibold">University of Technology</p>
                <p>Bachelor's Degree in Computer Science</p>
            </section>

            <section class="mb-6">
                <h3 class="text-lg font-semibold mb-2 text-orange-500">Languages</h3>
                <p>English <span class="inline-block w-24 h-2 bg-orange-200"><span class="inline-block h-2 bg-orange-500" style="width: 100%"></span></span></p>
                <p>Arabic <span class="inline-block w-24 h-2 bg-orange-200"><span class="inline-block h-2 bg-orange-500" style="width: 75%"></span></span></p>
                <p>French <span class="inline-block w-24 h-2 bg-orange-200"><span class="inline-block h-2 bg-orange-500" style="width: 50%"></span></span></p>
            </section>

            <section class="mb-6">
                <h3 class="text-lg font-semibold mb-2 text-orange-500">Personal Skills</h3>
                <div class="flex flex-wrap">
                    <span class="bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 mb-2">Problem Solving</span>
                    <span class="bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 mb-2">Teamwork</span>
                    <span class="bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 mb-2">Communication</span>
                    <span class="bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 mb-2">Creativity</span>
                </div>
            </section>

            <section class="mb-6">
                <h3 class="text-lg font-semibold mb-2 text-orange-500">Tools and Technologies</h3>
                <p>JavaScript <span class="inline-block w-24 h-2 bg-orange-200"><span class="inline-block h-2 bg-orange-500" style="width: 90%"></span></span></p>
                <p>React <span class="inline-block w-24 h-2 bg-orange-200"><span class="inline-block h-2 bg-orange-500" style="width: 85%"></span></span></p>
                <p>Node.js <span class="inline-block w-24 h-2 bg-orange-200"><span class="inline-block h-2 bg-orange-500" style="width: 80%"></span></span></p>
                <p>Python <span class="inline-block w-24 h-2 bg-orange-200"><span class="inline-block h-2 bg-orange-500" style="width: 75%"></span></span></p>
            </section>
        </div>

        <!-- Right Column -->
        <div class="w-2/3 p-6">
            <section class="mb-6">
                <h3 class="text-xl font-semibold mb-2 border-b-2 border-orange-500 pb-1">Contact Info</h3>
                <p><strong>Email:</strong> john.doe@example.com</p>
                <p><strong>Phone:</strong> +1 234 567 8900</p>
                <p><strong>Residence:</strong> Baghdad, Iraq</p>
                <p><strong>Date of birth:</strong> 1990-01-15</p>
                <p><strong>Gender:</strong> Male</p>

                <h4 class="font-semibold mt-2">Links & Websites</h4>
                <ul>
                    <li>Portfolio: www.johndoe-portfolio.com</li>
                    <li>LinkedIn: linkedin.com/in/johndoe</li>
                    <li>GitHub: github.com/johndoe</li>
                </ul>
            </section>

            <section class="mb-6">
                <h3 class="text-xl font-semibold mb-2 border-b-2 border-orange-500 pb-1">Summary</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
            </section>

            <section class="mb-6">
                <h3 class="text-xl font-semibold mb-2 border-b-2 border-orange-500 pb-1">Employment</h3>
                <div class="mb-4">
                    <p class="text-sm">2020 - Present</p>
                    <p class="font-semibold">Senior Developer at TechCorp</p>
                    <p class="font-semibold">Responsibilities:</p>
                    <ul class="list-disc list-inside">
                        <li>Lead development of web applications</li>
                        <li>Mentor junior developers</li>
                        <li>Implement best practices and coding standards</li>
                    </ul>
                </div>
                <div class="mb-4">
                    <p class="text-sm">2017 - 2020</p>
                    <p class="font-semibold">Full Stack Developer at WebSolutions</p>
                    <p class="font-semibold">Responsibilities:</p>
                    <ul class="list-disc list-inside">
                        <li>Developed and maintained client websites</li>
                        <li>Collaborated with design team for UI/UX improvements</li>
                        <li>Optimized application performance</li>
                    </ul>
                </div>
            </section>

            <section class="mb-6">
                <h3 class="text-xl font-semibold mb-2 border-b-2 border-orange-500 pb-1">Courses</h3>
                <div class="mb-2">
                    <p class="font-semibold">Advanced React Patterns</p>
                    <p>Provided By: Frontend Masters</p>
                    <p>Duration: 6 weeks</p>
                </div>
                <div class="mb-2">
                    <p class="font-semibold">Machine Learning Fundamentals</p>
                    <p>Provided By: Coursera</p>
                    <p>Duration: 12 weeks</p>
                </div>
            </section>

            <section class="mb-6">
                <h3 class="text-xl font-semibold mb-2 border-b-2 border-orange-500 pb-1">Events and Activities</h3>
                <div class="mb-2">
                    <p>2023</p>
                    <p>TechConf Middle East as <span class="text-orange-500">Speaker</span></p>
                </div>
                <div class="mb-2">
                    <p>2022</p>
                    <p>Local Hackathon as <span class="text-orange-500">Participant</span></p>
                </div>
            </section>
        </div>
    </div>
</div>
</body>
</html>
