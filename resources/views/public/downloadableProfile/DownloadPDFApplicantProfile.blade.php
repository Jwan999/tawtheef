<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $applicant->details['fullName'] ?? 'Applicant' }} - Resume</title>
    @vite('resources/css/app.css')
    <style>
        body {
            font-family: 'Dosis', sans-serif;
        }

        .rating-block {
            width: 30px;
            height: 10px;
            display: inline-block;
            margin-right: 2px;
        }

        .rating-block-filled {
            background-color: #E26600;
        }

        .rating-block-empty {
            background-color: #E2E8F0;
        }

        .list-circle {
            list-style-type: circle;
        }

        .section-header {
            font-size: 1.2rem;
            font-weight: 500;
            color: #000;
            border-bottom: 2px solid #E26600;
            padding-bottom: 0.5rem;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body class="font-sans text-dark bg-light">
<div class="max-w-7xl mx-auto p-8">
    <div class="flex">
        <!-- Left Column -->
        <div class="w-1/3 pr-8">
            <div class="mb-8 text-center">
                @php
                    $imagePath = $applicant->image ? storage_path('app/public/' . str_replace('storage/', '', $applicant->image)) : null;
                    $src = '';
                    if ($imagePath && file_exists($imagePath) && is_file($imagePath)) {
                        $imageData = base64_encode(file_get_contents($imagePath));
                        $mimeType = mime_content_type($imagePath);
                        $src = "data:{$mimeType};base64,{$imageData}";
                    }
               @endphp

                @if($src)
                    <img src="{{ $src }}" alt="Profile Picture" class="h-44 w-52 rounded-md object-cover mx-auto mb-4">
                @else
                    <div class="h-44 w-52 bg-gray-200 rounded-md mx-auto mb-4 flex items-center justify-center">
                        <span class="text-gray-500">No Image</span>
                    </div>
                @endif

                <h1 class="text-2xl font-bold text-orange mb-2">{{ $applicant->details['fullName'] ?? 'Applicant Name' }}</h1>
            </div>            <div class="mb-8">
                <h2 class="section-header">Personal Information</h2>
                <div class="space-y-2 text-sm">
                    <p>{{ $applicant->contact['fullName'] ?? '' }}</p>
                    <p>{{ $applicant->contact['email'] ?? '' }}</p>
                    <p>{{ $applicant->contact['phone'] ?? '' }}</p>
                    <p>{{ $applicant->contact['birthdate'] ?? '' }}</p>
                    <p>{{ $applicant->contact['city'] ?? '' }}{{ !empty($applicant->contact['zone']) ? ', ' . $applicant->contact['zone'] : '' }}</p>
                </div>
            </div>

            <div class="mb-8">
                <h2 class="section-header">Specializations</h2>
                <div class="rounded-lg overflow-hidden text-sm">
                    <div class="p-4 text-sm">
                        <ul class="list-disc pl-5 space-y-4">
                            @foreach($applicant->speciality['specializations'] ?? [] as $specialization)
                                <li class="font-semibold">
                                    {{ $specialization }}
                                    @if(!empty($applicant->speciality['children']))
                                        <p class="font-normal text-orange-500 mt-2">Experienced with the following:</p>
                                        <ul class="list-circle pl-5 mt-1">
                                            @foreach($applicant->speciality['children'] as $subSpecialization)
                                                <li class="font-normal">{{ $subSpecialization }}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @php
                // Custom classes for rating blocks
                $ratingBlockBase = 'w-6 h-2 mr-0.5 inline-block rounded-full';
                $ratingBlockFilled = $ratingBlockBase . ' bg-orange-500';
                $ratingBlockEmpty = $ratingBlockBase . ' bg-gray-300';
            @endphp

                <!-- Tools & Technologies Section -->
            <div class="mb-8">
                <h2 class="section-header">Tools & Technologies</h2>
                <div class="text-sm">
                    @foreach($applicant->tools ?? [] as $tool)
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm">{{ $tool['item'] }}</span>
                            <div>
                                @for ($i = 1; $i <= 5; $i++)
                                    <span
                                        class="{{ $i <= $tool['rating'] ? $ratingBlockFilled : $ratingBlockEmpty }}"></span>
                                @endfor
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Languages Section -->
            <div class="mb-8">
                <h2 class="section-header">Languages</h2>
                <div class="text-sm">
                    @foreach($applicant->languages ?? [] as $language)
                        @if(!empty($language['item']))
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm">{{ $language['item'] }}</span>
                                <div>
                                    @for ($i = 1; $i <= 5; $i++)
                                        <span
                                            class="{{ $i <= $language['rating'] ? $ratingBlockFilled : $ratingBlockEmpty }}"></span>
                                    @endfor
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            <div class="mb-8">
                <h2 class="section-header">Skills</h2>
                <div class="flex flex-wrap gap-2 text-sm">
                    @foreach($applicant->skills ?? [] as $skill)
                        <span class="bg-orange-50 text-orange px-3 py-1 rounded-full text-sm">{{ $skill }}</span>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="w-2/3 pl-8 border-l border-orange-50">
            <div class="mb-8">
                <h2 class="section-header">Summary</h2>
                <p class="text-justify text-sm">{{ $applicant->summary }}</p>
            </div>

            <div class="mb-8">
                <h2 class="section-header">Employment History</h2>
                @foreach($applicant->employment ?? [] as $job)
                    <div class="mb-4 text-sm">
                        <p class="font-semibold text-orange-500 text-xs">
                            {{ $job['duration'][0] ?? '' }} - {{ $job['duration'][1] ?? 'Present' }}
                        </p>
                        <p class="font-semibold">{{ $job['title'] ?? '' }} at {{ $job['employer'] ?? '' }}</p>
                        @if(!empty($job['responsibilities']))
                            <p class="my-3 text-xs">Responsibilities:</p>
                            <ul class="list-disc list-inside ml-4">
                                @foreach($job['responsibilities'] as $responsibility)
                                    <li>{{ $responsibility }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="mb-8">
                <h2 class="section-header">Educational Background</h2>
                @foreach($applicant->education ?? [] as $education)
                    @if(!empty($education['institute']) || !empty($education['degree']))
                        <div class="mb-4 text-sm">
                            <p class="font-semibold text-orange-500 text-xs">
                                {{ $education['duration'][0] ?? '' }} - {{ $education['duration'][1] ?? 'Present' }}
                            </p>
                            <p class="font-semibold">{{ $education['degree'] ?? '' }}</p>
                            <p>{{ $education['institute'] ?? '' }}</p>
                        </div>
                    @endif
                @endforeach
            </div>

            <div class="mb-8">
                <h2 class="section-header">Courses Taken</h2>
                @forelse($applicant->courses ?? [] as $course)
                    @if(!empty($course['title']))
                        <div class="mb-4 text-sm">
                            <p class="font-semibold">{{ $course['title'] ?? '' }}</p>
                            <p class="text-xs">Provided by: {{ $course['entity'] ?? '' }}</p>
                            <p class="text-xs">Duration: {{ $course['duration'] ?? '' }}</p>
                        </div>
                    @endif
                @empty
                    <p class="text-sm">No courses listed.</p>
                @endforelse
            </div>

            <div class="mb-8">
                <h2 class="section-header">Activities and Events</h2>
                @forelse($applicant->activities ?? [] as $activity)
                    @if(!empty($activity['title']))
                        <div class="mb-4 text-sm">
                            <p class="font-semibold">{{ $activity['title'] ?? '' }}</p>
                            <p>{{ $activity['participatedAs'] ?? '' }} ({{ $activity['year'] ?? '' }})</p>
                        </div>
                    @endif
                @empty
                    <p class="text-sm">No activities listed.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
</body>
</html>
