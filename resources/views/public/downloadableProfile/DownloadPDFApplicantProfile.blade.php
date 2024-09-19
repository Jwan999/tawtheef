<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dosis:wght@200..800&display=swap" rel="stylesheet">
    <title>{{ $applicant->contact['fullName'] ?? 'Applicant' }} - Resume</title>

    <style>
        body {
            font-family: 'Dosis', sans-serif;
            background-color: #ffffff;
            font-weight: 600;
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
            font-family: 'Dosis', sans-serif;
            font-size: 1.2rem;
            font-weight: 500;
            color: #3F3F46;
            border-bottom: 1px solid #3F3F46;
            padding-bottom: 0.5rem;
            margin-bottom: 1rem;
        }

        .text-sm {
            font-family: 'Dosis', sans-serif;
            font-size: 0.900rem;
        }

        .text-xs {
            font-family: 'Dosis', sans-serif;
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
            background-color: #F4F4F5;
            color: #3F3F46;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            display: inline-block;
            margin: 0.25rem;
        }

        .profile-picture-container {
            border-radius: 1rem;
        }

        .profile-picture {
            object-fit: cover;
            border-radius: 1rem;
            object-position: center;
            height: 12rem;
            width: 100%;
        }

        .h-32 {
            height: 8rem;
        }

        .w-32 {
            width: 8rem;
        }

        .fill-dark {
            fill: #3f3f46;
        }

        .competency-level {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
            background-color: #3f3f46;
            color: white;
            display: inline-block;
        }
    </style>
</head>
<body>
<div class="container">
    <table class="resume-layout">
        <tr>
            <!-- Left Column -->
            <td class="left-column">
                <!-- Profile Picture and Name -->
                <div style="text-align: center; margin-bottom: 2rem;">
                    @if($applicant->image)
                        <div class="profile-picture-container">
                            <img src="{{$applicant->image}}" id="profilePicture" alt="Profile Picture" class="profile-picture">
                        </div>
                    @else
                        <div class="profile-picture-container" style="background-color: #F4F4F5; display: flex; align-items: center; justify-content: center; padding-top:2rem; padding-bottom:2rem;">
                            <svg class="h-32 w-32 fill-dark" viewBox="-42 0 512 512.002" xmlns="http://www.w3.org/2000/svg">
                                <path d="m210.351562 246.632812c33.882813 0 63.222657-12.152343 87.195313-36.128906 23.972656-23.972656 36.125-53.304687 36.125-87.191406 0-33.875-12.152344-63.210938-36.128906-87.191406-23.976563-23.96875-53.3125-36.121094-87.191407-36.121094-33.886718 0-63.21875 12.152344-87.191406 36.125s-36.128906 53.308594-36.128906 87.1875c0 33.886719 12.15625 63.222656 36.132812 87.195312 23.976563 23.96875 53.3125 36.125 87.1875 36.125zm0 0"/>
                                <path d="m426.128906 393.703125c-.691406-9.976563-2.089844-20.859375-4.148437-32.351563-2.078125-11.578124-4.753907-22.523437-7.957031-32.527343-3.308594-10.339844-7.808594-20.550781-13.371094-30.335938-5.773438-10.15625-12.554688-19-20.164063-26.277343-7.957031-7.613282-17.699219-13.734376-28.964843-18.199219-11.226563-4.441407-23.667969-6.691407-36.976563-6.691407-5.226563 0-10.28125 2.144532-20.042969 8.5-6.007812 3.917969-13.035156 8.449219-20.878906 13.460938-6.707031 4.273438-15.792969 8.277344-27.015625 11.902344-10.949219 3.542968-22.066406 5.339844-33.039063 5.339844-10.972656 0-22.085937-1.796876-33.046874-5.339844-11.210938-3.621094-20.296876-7.625-26.996094-11.898438-7.769532-4.964844-14.800782-9.496094-20.898438-13.46875-9.75-6.355468-14.808594-8.5-20.035156-8.5-13.3125 0-25.75 2.253906-36.972656 6.699219-11.257813 4.457031-21.003906 10.578125-28.96875 18.199219-7.605469 7.28125-14.390625 16.121094-20.15625 26.273437-5.558594 9.785157-10.058594 19.992188-13.371094 30.339844-3.199219 10.003906-5.875 20.945313-7.953125 32.523437-2.058594 11.476563-3.457031 22.363282-4.148437 32.363282-.679688 9.796875-1.023438 19.964844-1.023438 30.234375 0 26.726562 8.496094 48.363281 25.25 64.320312 16.546875 15.746094 38.441406 23.734375 65.066406 23.734375h246.53125c26.625 0 48.511719-7.984375 65.0625-23.734375 16.757813-15.945312 25.253906-37.585937 25.253906-64.324219-.003906-10.316406-.351562-20.492187-1.035156-30.242187zm0 0"/>
                            </svg>
                        </div>
                    @endif
                    <h1 style="font-size: 1.5rem; font-weight: bold; color: #3f3f46; margin-bottom: 0.5rem;">{{ $applicant->contact['fullName'] ?? 'Applicant Name' }}</h1>
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


                <!-- Tools & Technologies Section -->
                <div style="margin-bottom: 2rem;">
                    <h2 class="section-header">Tools & Technologies</h2>
                    <div class="text-sm">
                        @foreach($applicant->tools ?? [] as $tool)
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                                <span>{{ $tool['item'] }}</span>
                                <span class="competency-level">
                                    @switch($tool['rating'])
                                        @case(1)
                                            Basic
                                            @break
                                        @case(2)
                                            Intermediate
                                            @break
                                        @case(3)
                                            Good
                                            @break
                                        @case(4)
                                            Very Good
                                            @break
                                        @case(5)
                                            Excellent
                                            @break
                                        @default
                                            N/A
                                    @endswitch
                                </span>
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
                                    <span class="competency-level">
                                        @switch($language['rating'])
                                            @case(1)
                                                Basic
                                                @break
                                            @case(2)
                                                Intermediate
                                                @break
                                            @case(3)
                                                Good
                                                @break
                                            @case(4)
                                                Very Good
                                                @break
                                            @case(5)
                                                Excellent
                                                @break
                                            @default
                                                N/A
                                        @endswitch
                                    </span>
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
                <!-- Specializations -->
                <div style="margin-bottom: 2rem;">
                    <h2 class="section-header">Specializations</h2>
                    <div class="text-sm">
                        <ul class="list-disc">
                            @foreach($applicant->speciality['specializations'] ?? [] as $specialization)
                                <li style="font-weight: 600; margin-bottom: 1rem;">
                                    {{ $specialization }}
                                </li>
                            @endforeach
                            @if(!empty($applicant->speciality['children']))
                                <p style="font-weight: normal; color: #3f3f46; margin-top: 0.5rem;">Experienced with the following:</p>
                                <ul class="list-circle">
                                    @foreach($applicant->speciality['children'] as $subSpecialization)
                                        <li style="font-weight: normal;">{{ $subSpecialization }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </ul>
                    </div>
                </div>
                <!-- Employment History -->
                <div style="margin-bottom: 2rem;">
                    <h2 class="section-header">Employment History</h2>
                    @foreach($applicant->employment ?? [] as $job)
                        <div style="margin-bottom: 1rem; font-size: 0.875rem;">
                            <p style="font-weight: 600; color: #3f3f46; font-size: 0.75rem;">
                                {{ $job['duration'][0] ?? '' }} - {{ $job['duration'][1] ?? 'Present' }}
                            </p>
                            <p style="font-weight: 600;">{{ $job['title'] ?? '' }} at {{ $job['employer'] ?? '' }}</p>
                            @if(!empty($job['responsibilities']))
                                <p style="margin-top: 0.75rem; margin-bottom: 0.75rem; font-size: 0.75rem;">
                                    Responsibilities:</p>
                                <ul style="list-style-type: disc; margin-left: 1rem;">
                                    @foreach($job['responsibilities'] as $responsibility)
                                        <li>{{ $responsibility }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Educational Background (continued) -->
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
</body>
</html>
