<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $applicant->contact['fullName'] ?? 'Applicant' }} - Resume</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dosis:wght@200..800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Dosis', sans-serif;
            color: #1f2937;
            background-color: #ffffff;
        }
        .container {
            margin-left: auto;
            margin-right: auto;
            padding: 2rem;
        }
        .resume-layout {
            width: 100%;
            border-collapse: collapse;
        }
        .left-column {
            width: 33.5%;
            padding-right: 2rem;
            vertical-align: top;
        }
        .right-column {
            width: 62.5%;
            vertical-align: top;
        }
        .section-header {
            font-size: 1.2rem;
            font-weight: 500;
            color: #000;
            border-bottom: 2px solid #E26600;
            padding-bottom: 0.5rem;
            margin-bottom: 1rem;
        }
        .text-sm {
            font-size: 0.900rem;
        }
        .text-xs {
            font-size: 0.75rem;
        }
        .space-y-2 > * + * {
            margin-top: 0.5rem;
        }
        .list-disc {
            list-style-type: disc;
            padding-left: 1.5rem;
        }
        .list-circle {
            list-style-type: circle;
            padding-left: 1.25rem;
        }
        .skill-tag {
            background-color: #fff7ed;
            color: #f97316;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            display: inline-block;
            margin: 0.25rem;
        }
        .profile-picture-container {
            width: 300px;
            height: 300px;
            margin: 0 auto 1rem auto;
            overflow: hidden;
            border-radius: 1rem;
        }
        .profile-picture {
            width: 100%;
            height: 100%;
            object-fit: fill;
            border-radius: 1rem;
            object-position: center;
        }

    </style>
</head>
<body>
<div class="container">

    <table class="resume-layout">
        <tr>
            <!-- Left Column -->
            <td class="left-column">
                <div style="text-align: center; margin-bottom: 2rem;">
{{--                    {{$applicant->image}}--}}
                    @if($applicant->image)
                        <div class="profile-picture-container">
                            <img src="{{$applicant->image}}" id="profilePicture" alt="Profile Picture" class="profile-picture">
                        </div>
                    @else
                        <div class="profile-picture-container" style="background-color: #E2E8F0; display: flex; align-items: center; justify-content: center;">
                            <span style="color: #6B7280;">No Image</span>
                        </div>
                    @endif
                    <h1 style="font-size: 1.5rem; font-weight: bold; color: #E26600; margin-bottom: 0.5rem;">{{ $applicant->contact['fullName'] ?? 'Applicant Name' }}</h1>
                </div>


                <!-- Personal Information -->
                <div style="margin-bottom: 2rem;">
                    <h2 class="section-header">Personal Information</h2>
                    <div class="space-y-2 text-sm">
                        <p>{{ $applicant->contact['fullName'] ?? '' }}</p>
                        <p>{{ $applicant->contact['email'] ?? '' }}</p>
                        <p>{{ $applicant->contact['phone'] ?? '' }}</p>
                        <p>{{ $applicant->contact['birthdate'] ?? '' }}</p>
                        <p>{{ $applicant->contact['city'] ?? '' }}{{ !empty($applicant->contact['zone']) ? ', ' . $applicant->contact['zone'] : '' }}</p>
                    </div>
                </div>

                <!-- Specializations -->
                <div style="margin-bottom: 2rem;">
                    <h2 class="section-header">Specializations</h2>
                    <div class="text-sm">
                        <ul class="list-disc">
                            @foreach($applicant->speciality['specializations'] ?? [] as $specialization)
                                <li style="font-weight: 600; margin-bottom: 1rem;">
                                    {{ $specialization }}
                                    @if(!empty($applicant->speciality['children']))
                                        <p style="font-weight: normal; color: #f97316; margin-top: 0.5rem;">Experienced with the following:</p>
                                        <ul class="list-circle">
                                            @foreach($applicant->speciality['children'] as $subSpecialization)
                                                <li style="font-weight: normal;">{{ $subSpecialization }}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- Tools & Technologies Section -->
                <div style="margin-bottom: 2rem;">
                    <h2 class="section-header">Tools & Technologies</h2>
                    <div class="text-sm">
                        @foreach($applicant->tools ?? [] as $tool)
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                                <span>{{ $tool['item'] }}</span>
                                <div>
                                    @for ($i = 1; $i <= 5; $i++)
                                        <span style="width: 1.5rem; height: 0.5rem; margin-right: 0.125rem; display: inline-block; border-radius: 9999px; background-color: {{ $i <= $tool['rating'] ? '#f97316' : '#d1d5db' }};"></span>
                                    @endfor
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Languages Section -->
                <div style="margin-bottom: 2rem;">
                    <h2 class="section-header">Languages</h2>
                    <div class="text-sm">
                        @foreach($applicant->languages ?? [] as $language)
                            @if(!empty($language['item']))
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                                    <span>{{ $language['item'] }}</span>
                                    <div>
                                        @for ($i = 1; $i <= 5; $i++)
                                            <span style="width: 1.5rem; height: 0.5rem; margin-right: 0.125rem; display: inline-block; border-radius: 9999px; background-color: {{ $i <= $language['rating'] ? '#f97316' : '#d1d5db' }};"></span>
                                        @endfor
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>

                <!-- Skills -->
                <div style="margin-bottom: 2rem;">
                    <h2 class="section-header">Skills</h2>
                    <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                        @foreach($applicant->skills ?? [] as $skill)
                            <span class="skill-tag">{{ $skill }}</span>
                        @endforeach
                    </div>
                </div>
            </td>

            <!-- Right Column -->
            <td class="right-column">
                <!-- Summary -->
                <div style="margin-bottom: 2rem;">
                    <h2 class="section-header">Summary</h2>
                    <p style="text-align: justify; font-size: 0.875rem;">{{ $applicant->summary }}</p>
                </div>

                <!-- Employment History -->
                <div style="margin-bottom: 2rem;">
                    <h2 class="section-header">Employment History</h2>
                    @foreach($applicant->employment ?? [] as $job)
                        <div style="margin-bottom: 1rem; font-size: 0.875rem;">
                            <p style="font-weight: 600; color: #f97316; font-size: 0.75rem;">
                                {{ $job['duration'][0] ?? '' }} - {{ $job['duration'][1] ?? 'Present' }}
                            </p>
                            <p style="font-weight: 600;">{{ $job['title'] ?? '' }} at {{ $job['employer'] ?? '' }}</p>
                            @if(!empty($job['responsibilities']))
                                <p style="margin-top: 0.75rem; margin-bottom: 0.75rem; font-size: 0.75rem;">Responsibilities:</p>
                                <ul style="list-style-type: disc; margin-left: 1rem;">
                                    @foreach($job['responsibilities'] as $responsibility)
                                        <li>{{ $responsibility }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Educational Background -->
                <div style="margin-bottom: 2rem;">
                    <h2 class="section-header">Educational Background</h2>
                    @foreach($applicant->education ?? [] as $education)
                        @if(!empty($education['institute']) || !empty($education['degree']))
                            <div style="margin-bottom: 1rem; font-size: 0.875rem;">
                                <p style="font-weight: 600; color: #f97316; font-size: 0.75rem;">
                                    {{ $education['duration'][0] ?? '' }} - {{ $education['duration'][1] ?? 'Present' }}
                                </p>
                                <p style="font-weight: 600;">{{ $education['degree'] ?? '' }}</p>
                                <p>{{ $education['institute'] ?? '' }}</p>
                            </div>
                        @endif
                    @endforeach
                </div>

                <!-- Courses Taken -->
                <div style="margin-bottom: 2rem;">
                    <h2 class="section-header">Courses Taken</h2>
                    @forelse($applicant->courses ?? [] as $course)
                        @if(!empty($course['title']))
                            <div style="margin-bottom: 1rem; font-size: 0.875rem;">
                                <p style="font-weight: 600;">{{ $course['title'] ?? '' }}</p>
                                <p style="font-size: 0.75rem;">Provided by: {{ $course['entity'] ?? '' }}</p>
                                <p style="font-size: 0.75rem;">Duration: {{ $course['duration'] ?? '' }}</p>
                            </div>
                        @endif
                    @empty
                        <p style="font-size: 0.875rem;">No courses listed.</p>
                    @endforelse
                </div>

                <!-- Activities and Events -->
                <div style="margin-bottom: 2rem;">
                    <h2 class="section-header">Activities and Events</h2>
                    @forelse($applicant->activities ?? [] as $activity)
                        @if(!empty($activity['title']))
                            <div style="margin-bottom: 1rem; font-size: 0.875rem;">
                                <p style="font-weight: 600;">{{ $activity['title'] ?? '' }}</p>
                                <p>{{ $activity['participatedAs'] ?? '' }} ({{ $activity['year'] ?? '' }})</p>
                            </div>
                        @endif
                    @empty
                        <p style="font-size: 0.875rem;">No activities listed.</p>
                    @endforelse
                </div>
            </td>
        </tr>
    </table>
</div>
<script>
    // window.addEventListener('load', function() {
    //     // Fallback in case the image doesn't load
    //     setTimeout(function() {
    //         window.status = 'ready';
    //     }, 1000);
    // });
</script>
</body>
</html>
