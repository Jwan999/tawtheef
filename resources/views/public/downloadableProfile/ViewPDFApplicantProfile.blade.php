<!DOCTYPE html>
<html lang="en" style="font-family: 'Dosis', sans-serif; background-color: #ffffff; font-weight: 600;">
<head>
    <meta charset="UTF-8">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dosis:wght@200..800&display=swap" rel="stylesheet">
    <title>{{ $applicant->contact['fullName'] ?? 'Applicant' }} - Resume</title>
</head>
<body style="font-family: 'Dosis', sans-serif; background-color: #ffffff; font-weight: 600;">
<div style="margin-left: auto; margin-right: auto; padding: 2rem;">
    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <!-- Left Column -->
            <td style="width: 33.5%; padding-right: 2rem; vertical-align: top;">
                <!-- Profile Picture and Name -->
                <div style="text-align: center; margin-bottom: 2rem;">
                    @if($applicant->image)
                        <div style="border-radius: 1rem;">
                            <img src="{{$applicant->image}}" id="profilePicture" alt="Profile Picture"
                                 style="object-fit: cover; border-radius: 1rem; object-position: center; height: 16rem; width: 100%;">
                        </div>
                    @else
                        <div
                            style="border-radius: 1rem; background-color: #F4F4F5; display: flex; align-items: center; justify-content: center; padding-top:2rem; padding-bottom:2rem;">
                            <svg style="height: 8rem; width: 8rem; fill: #3f3f46;" viewBox="-42 0 512 512.002"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="m210.351562 246.632812c33.882813 0 63.222657-12.152343 87.195313-36.128906 23.972656-23.972656 36.125-53.304687 36.125-87.191406 0-33.875-12.152344-63.210938-36.128906-87.191406-23.976563-23.96875-53.3125-36.121094-87.191407-36.121094-33.886718 0-63.21875 12.152344-87.191406 36.125s-36.128906 53.308594-36.128906 87.1875c0 33.886719 12.15625 63.222656 36.132812 87.195312 23.976563 23.96875 53.3125 36.125 87.1875 36.125zm0 0"/>
                                <path
                                    d="m426.128906 393.703125c-.691406-9.976563-2.089844-20.859375-4.148437-32.351563-2.078125-11.578124-4.753907-22.523437-7.957031-32.527343-3.308594-10.339844-7.808594-20.550781-13.371094-30.335938-5.773438-10.15625-12.554688-19-20.164063-26.277343-7.957031-7.613282-17.699219-13.734376-28.964843-18.199219-11.226563-4.441407-23.667969-6.691407-36.976563-6.691407-5.226563 0-10.28125 2.144532-20.042969 8.5-6.007812 3.917969-13.035156 8.449219-20.878906 13.460938-6.707031 4.273438-15.792969 8.277344-27.015625 11.902344-10.949219 3.542968-22.066406 5.339844-33.039063 5.339844-10.972656 0-22.085937-1.796876-33.046874-5.339844-11.210938-3.621094-20.296876-7.625-26.996094-11.898438-7.769532-4.964844-14.800782-9.496094-20.898438-13.46875-9.75-6.355468-14.808594-8.5-20.035156-8.5-13.3125 0-25.75 2.253906-36.972656 6.699219-11.257813 4.457031-21.003906 10.578125-28.96875 18.199219-7.605469 7.28125-14.390625 16.121094-20.15625 26.273437-5.558594 9.785157-10.058594 19.992188-13.371094 30.339844-3.199219 10.003906-5.875 20.945313-7.953125 32.523437-2.058594 11.476563-3.457031 22.363282-4.148437 32.363282-.679688 9.796875-1.023438 19.964844-1.023438 30.234375 0 26.726562 8.496094 48.363281 25.25 64.320312 16.546875 15.746094 38.441406 23.734375 65.066406 23.734375h246.53125c26.625 0 48.511719-7.984375 65.0625-23.734375 16.757813-15.945312 25.253906-37.585937 25.253906-64.324219-.003906-10.316406-.351562-20.492187-1.035156-30.242187zm0 0"/>
                            </svg>
                        </div>
                    @endif
                    <h1 style="font-size: 1.5rem; font-weight: bold; color: #3f3f46; margin-bottom: 0.5rem;">{{ $applicant->contact['fullName'] ?? 'Applicant Name' }}</h1>
                </div>

                <!-- Personal Information -->
                <div style="margin-bottom: 2rem;">
                    <h2 style="font-family: 'Dosis', sans-serif; font-size: 1.3rem; font-weight: 500; color: #3F3F46; border-bottom: 1px solid #3F3F46; padding-bottom: 0.5rem; margin-bottom: 1rem;">
                        Personal Information</h2>
                    <div style="font-family: 'Dosis', sans-serif; font-size: 0.900rem;">
                        <p style="margin-top: 0.5rem;">{{ $applicant->contact['email'] ?? '' }}</p>
                        <p style="margin-top: 0.5rem;">{{ $applicant->contact['phone'] ?? '' }}</p>
                        <p style="margin-top: 0.5rem;">{{ $applicant->contact['birthdate'] ?? '' }}</p>
                        <p style="margin-top: 0.5rem;">
                            {{ $applicant->contact['city'] ?? '' }}
                            @if(!empty($applicant->contact['zone']) && $applicant->contact['zone'] !== 'Choose your zone...')
                                , {{ $applicant->contact['zone'] }}
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Tools & Technologies Section -->
                <div style="margin-bottom: 2rem;">
                    <h2 style="font-family: 'Dosis', sans-serif; font-size: 1.3rem; font-weight: 500; color: #3F3F46; border-bottom: 1px solid #3F3F46; padding-bottom: 0.5rem; margin-bottom: 1rem;">
                        Tools & Technologies</h2>
                    <div style="font-family: 'Dosis', sans-serif; font-size: 0.900rem;">
                        @foreach($applicant->tools ?? [] as $tool)
                            <div
                                style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                                <span>{{ $tool['item'] }}</span>
                                <span
                                    style="font-size: 0.75rem; padding: 0.25rem 0.5rem; border-radius: 9999px; background-color: #3f3f46; color: white; display: inline-block;">
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
                    <h2 style="font-family: 'Dosis', sans-serif; font-size: 1.3rem; font-weight: 500; color: #3F3F46; border-bottom: 1px solid #3F3F46; padding-bottom: 0.5rem; margin-bottom: 1rem;">
                        Languages</h2>
                    <div style="font-family: 'Dosis', sans-serif; font-size: 0.900rem;">
                        @foreach($applicant->languages ?? [] as $language)
                            @if(!empty($language['item']))
                                <div
                                    style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                                    <span>{{ $language['item'] }}</span>
                                    <span
                                        style="font-size: 0.75rem; padding: 0.25rem 0.5rem; border-radius: 9999px; background-color: #3f3f46; color: white; display: inline-block;">
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
                    <h2 style="font-family: 'Dosis', sans-serif; font-size: 1.3rem; font-weight: 500; color: #3F3F46; border-bottom: 1px solid #3F3F46; padding-bottom: 0.5rem; margin-bottom: 1rem;">
                        Skills</h2>
                    <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                        @foreach($applicant->skills ?? [] as $skill)
                            <span
                                style="background-color: #F4F4F5; color: #3F3F46; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 1rem; display: inline-block; margin: 0.25rem;">{{ $skill }}</span>
                        @endforeach
                    </div>
                </div>
            </td>

            <!-- Right Column -->
            <td style="width: 62.5%; vertical-align: top;">
                <!-- Summary -->
                <div style="margin-bottom: 2rem;">
                    <h2 style="font-family: 'Dosis', sans-serif; font-size: 1.3rem; font-weight: 300; color: #3F3F46; border-bottom: 1px solid #3F3F46; padding-bottom: 0.5rem; margin-bottom: 1rem;">
                        Summary</h2>
                    <p style="text-align: justify;line-height: 1.5rem;; font-size: 1rem;">{{ $applicant->summary }}</p>
                </div>

                <!-- Specializations -->
                <div style="margin-bottom: 2rem;">
                    <h2 style="font-family: 'Dosis', sans-serif; font-size: 1.3rem; font-weight: 500; color: #3F3F46; border-bottom: 1px solid #3F3F46; padding-bottom: 0.5rem; margin-bottom: 1rem;">
                        Specializations</h2>
                    <div style="font-family: 'Dosis', sans-serif; font-size: 1rem;">
                        @php
                            $specialities = [
                                [
                                    "title" => "Creative & Design",
                                    "children" => ["Graphic Design", "User Experience (UX) Design", "User Interface (UI) Design", "Visual Design", "Motion Graphics", "Interaction Design", "Product Design", "Content Design", "Fashion Design", "Interior Design", "Architecture"]
                                ],
                                [
                                    "title" => "Development",
                                    "children" => ["Front-End Development", "Back-End Development", "Full-Stack Development", "Web Development", "Mobile Development", "UI/UX Design", "Software Development", "Data Science", "Machine Learning", "Artificial Intelligence", "DevOps", "Cloud Computing"]
                                ],
                                [
                                    "title" => "Business & Management",
                                    "children" => ["Marketing", "Sales", "Project Management", "Operations Management"]
                                ],
                                [
                                    "title" => "Writing & Editing",
                                    "children" => ["Copywriting", "Content Writing", "Technical Writing", "Editing", "Proofreading"]
                                ],
                                [
                                    "title" => "Science & Engineering",
                                    "children" => ["Biology", "Chemistry", "Engineering", "Mathematics", "Statistics", "Physics"]
                                ],
                                [
                                    "title" => "Other",
                                    "children" => ["Education & Training", "Healthcare", "Law", "Social Work", "Communications", "Public Relations", "Customer Service", "Translation & Interpretation", "Undecided yet"]
                                ]
                            ];
                        @endphp

                        @foreach($applicant->speciality['specializations'] ?? [] as $specialization)
                            <div style="margin-bottom: 1.5rem;">
                                <h3 style="font-weight: 700; font-size: 1.1rem; color: #2D3748; margin-bottom: 0.5rem;">
                                    {{ $specialization }}
                                </h3>
                                @if(!empty($applicant->speciality['children']))
                                    <ul style="list-style-type: disc; padding-left: 1.5rem; margin-top: 0;">
                                        @foreach($applicant->speciality['children'] as $subSpecialization)
                                            @foreach($specialities as $speciality)
                                                @if($speciality['title'] === $specialization && in_array($subSpecialization, $speciality['children']))
                                                    <li style="font-weight: normal; margin-bottom: 0.4rem;">{{ $subSpecialization }}</li>
                                                @endif
                                            @endforeach
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Educational Background -->
                <div style="margin-bottom: 2rem;">
                    <h2 style="font-family: 'Dosis', sans-serif; font-size: 1.3rem; font-weight: 500; color: #3F3F46; border-bottom: 1px solid #3F3F46; padding-bottom: 0.5rem; margin-bottom: 1rem;">
                        Educational Background</h2>
                    @foreach($applicant->education ?? [] as $education)
                        @if(!empty($education['institute']) || !empty($education['degree']))
                            <div style="margin-bottom: 1.6rem;">

                                <span
                                    style="font-weight: 700; font-size: 1rem;">{{ $education['degree'] ?? '' }}, </span>
                                <span style="font-size: 1rem; ">{{ $education['institute'] ?? '' }}</span>
                                <p style="font-weight: 600;; font-size: 0.9rem; margin-bottom: 0.5rem; color: #666;">
                                    {{ $education['duration'][0] ?? '' }} - {{ $education['duration'][1] ?? 'Present' }}
                                </p>
                            </div>
                        @endif
                    @endforeach
                </div>
                <!-- Employment History -->
                <div style="margin-bottom: 2rem;">
                    <h2 style="font-family: 'Dosis', sans-serif; font-size: 1.3rem; font-weight: 500; color: #3F3F46; border-bottom: 1px solid #3F3F46; padding-bottom: 0.5rem; margin-bottom: 1rem;">
                        Employment History</h2>
                    @foreach($applicant->employment ?? [] as $job)
                        <div style="margin-bottom: 1.5rem;">

                            <span style="font-weight: 700; font-size: 1.1rem; margin-bottom: 0.25rem;">{{ $job['title'] ?? '' }}, </span>
                            <span style="font-size: 1rem; margin-bottom: 0.75rem;">{{ $job['employer'] ?? '' }}</span>
                            <p style="font-weight: 600;; font-size: 0.9rem; margin-bottom: 0.8rem; color: #666;">
                                {{ $job['duration'][0] ?? '' }} - {{ $job['duration'][1] ?? 'Present' }}
                            </p>
                            @if(!empty($job['responsibilities']))
                                <p style="font-weight: 600; font-size: 1rem; margin-bottom: 0.5rem;">
                                    Responsibilities:</p>
                                <div style="font-size: 0.95rem; color: #4B5563;">
                                    @foreach($job['responsibilities'] as $responsibility)
                                        <p style="margin-bottom: 0.10rem;">{{ $responsibility }}</p>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Courses Taken -->
                <div style="margin-bottom: 2rem;">
                    <h2 style="font-family: 'Dosis', sans-serif; font-size: 1.3rem; font-weight: 500; color: #3F3F46; border-bottom: 1px solid #3F3F46; padding-bottom: 0.5rem; margin-bottom: 1rem;">
                        Courses Taken</h2>
                    @forelse($applicant->courses ?? [] as $course)
                        @if(!empty($course['title']))
                            <div style="margin-bottom: 1rem; font-size: 1rem;">
                                <span style="font-weight: 600;">{{ $course['title'] ?? '' }}, </span>
                                <span style="">provided by: {{ $course['entity'] ?? '' }}</span>
                                <p style="font-weight: 600;; font-size: 0.9rem; margin-bottom: 0.5rem; color: #666;">
                                    Duration: {{ $course['duration'] ?? '' }}</p>
                            </div>
                        @endif
                    @empty
                        <p style="font-size: 1rem;">No courses listed.</p>
                    @endforelse
                </div>

                <!-- Activities and Events -->
                <div style="margin-bottom: 2rem;">
                    <h2 style="font-family: 'Dosis', sans-serif; font-size: 1.3rem; font-weight: 500; color: #3F3F46; border-bottom: 1px solid #3F3F46; padding-bottom: 0.5rem; margin-bottom: 1rem;">
                        Activities and Events</h2>
                    @forelse($applicant->activities ?? [] as $activity)
                        @if(!empty($activity['title']))
                            <div style="margin-bottom: 1rem; font-size: 1rem;">
                                <div style="display: flex; align-items: center;">
                                    <span style="font-weight: 600;">{{ $activity['title'] ?? '' }},</span>
                                    <span style="margin-left: 0.5rem;">{{ $activity['participatedAs'] ?? '' }}</span>
                                </div>
                                <p style="margin-top: 0.25rem; font-size: 0.9rem; color: #666;">{{ $activity['year'] ?? '' }}</p>
                            </div>
                        @endif
                    @empty
                        <p style="font-size: 1rem;">No activities listed.</p>
                    @endforelse
                </div>
            </td>
        </tr>
    </table>
</div>
</body>
</html>
