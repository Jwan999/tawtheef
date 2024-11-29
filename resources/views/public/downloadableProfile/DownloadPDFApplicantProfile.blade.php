<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Dosis:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Dosis', sans-serif;
            line-height: 1.6;
            color: #1a1a1a;
            max-width: 850px;
            margin: 0 auto;
            padding: 2rem;
        }

        /* Header section with image */
        .header {
            display: flex;
            align-items: flex-start;
            gap: 2rem;
            border-bottom: 2px solid #1a1a1a;
            padding-bottom: 1.5rem;
            margin-bottom: 2rem;
        }

        .profile-image {
            width: 150px;
            height: 150px;
            border-radius: 4px;
            object-fit: cover;
            flex-shrink: 0;
        }

        .profile-image-placeholder {
            width: 150px;
            height: 150px;
            background-color: #f4f4f5;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .profile-image-placeholder svg {
            width: 60%;
            height: 60%;
            fill: #3f3f46;
        }

        .header-content {
            flex-grow: 1;
        }

        .name {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            letter-spacing: 0.02em;
        }

        .contact-info {
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
            font-size: 1rem;
            font-weight: 500;
        }

        /* Main content layout */
        .main-content {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 3rem;
        }

        /* Section styling */
        .section {
            margin-bottom: 2.5rem;
        }

        .section-title {
            font-size: 1.3rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 1px solid #1a1a1a;
            padding-bottom: 0.5rem;
            margin-bottom: 1rem;
        }

        /* Experience items */
        .experience-item {
            margin-bottom: 1.5rem;
        }

        .experience-header {
            margin-bottom: 0.5rem;
        }

        .experience-title {
            font-weight: 600;
            font-size: 1.1rem;
        }

        .experience-company {
            font-weight: 500;
        }

        .experience-date {
            font-size: 0.95rem;
            color: #666;
            font-weight: 500;
        }

        .responsibilities {
            padding-left: 1.2rem;
            font-weight: 500;
        }

        .responsibilities li {
            margin-bottom: 0.3rem;
        }

        /* Skills and languages */
        .skill-item, .language-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .skill-level, .language-level {
            font-size: 0.9rem;
            color: #666;
        }

        /* Print styles */
        @media print {
            body {
                padding: 0.5in;
            }

            .main-content {
                gap: 2rem;
            }

            .section {
                break-inside: avoid;
            }

            .profile-image, .profile-image-placeholder {
                width: 120px;
                height: 120px;
            }
        }

        /* Mobile responsiveness */
        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .contact-info {
                justify-content: center;
            }

            .main-content {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .profile-image, .profile-image-placeholder {
                width: 120px;
                height: 120px;
            }
        }
    </style>
</head>
<body>
<header class="header">
    @if($applicant->image)
        <img src="{{ $applicant->image }}" alt="Profile Picture" class="profile-image">
    @else
        <div class="profile-image-placeholder">
            <svg viewBox="-42 0 512 512.002" xmlns="http://www.w3.org/2000/svg">
                <path d="m210.351562 246.632812c33.882813 0 63.222657-12.152343 87.195313-36.128906 23.972656-23.972656 36.125-53.304687 36.125-87.191406 0-33.875-12.152344-63.210938-36.128906-87.191406-23.976563-23.96875-53.3125-36.121094-87.191407-36.121094-33.886718 0-63.21875 12.152344-87.191406 36.125s-36.128906 53.308594-36.128906 87.1875c0 33.886719 12.15625 63.222656 36.132812 87.195312 23.976563 23.96875 53.3125 36.125 87.1875 36.125zm0 0"/>
                <path d="m426.128906 393.703125c-.691406-9.976563-2.089844-20.859375-4.148437-32.351563-2.078125-11.578124-4.753907-22.523437-7.957031-32.527343-3.308594-10.339844-7.808594-20.550781-13.371094-30.335938-5.773438-10.15625-12.554688-19-20.164063-26.277343-7.957031-7.613282-17.699219-13.734376-28.964843-18.199219-11.226563-4.441407-23.667969-6.691407-36.976563-6.691407-5.226563 0-10.28125 2.144532-20.042969 8.5-6.007812 3.917969-13.035156 8.449219-20.878906 13.460938-6.707031 4.273438-15.792969 8.277344-27.015625 11.902344-10.949219 3.542968-22.066406 5.339844-33.039063 5.339844-10.972656 0-22.085937-1.796876-33.046874-5.339844-11.210938-3.621094-20.296876-7.625-26.996094-11.898438-7.769532-4.964844-14.800782-9.496094-20.898438-13.46875-9.75-6.355468-14.808594-8.5-20.035156-8.5-13.3125 0-25.75 2.253906-36.972656 6.699219-11.257813 4.457031-21.003906 10.578125-28.96875 18.199219-7.605469 7.28125-14.390625 16.121094-20.15625 26.273437-5.558594 9.785157-10.058594 19.992188-13.371094 30.339844-3.199219 10.003906-5.875 20.945313-7.953125 32.523437-2.058594 11.476563-3.457031 22.363282-4.148437 32.363282-.679688 9.796875-1.023438 19.964844-1.023438 30.234375 0 26.726562 8.496094 48.363281 25.25 64.320312 16.546875 15.746094 38.441406 23.734375 65.066406 23.734375h246.53125c26.625 0 48.511719-7.984375 65.0625-23.734375 16.757813-15.945312 25.253906-37.585937 25.253906-64.324219-.003906-10.316406-.351562-20.492187-1.035156-30.242187zm0 0"/>
            </svg>
        </div>
    @endif
    <div class="header-content">
        <h1 class="name">{{ $applicant->contact['fullName'] }}</h1>
        <div class="contact-info">
            <span>{{ $applicant->contact['email'] }}</span>
            @if(isset($applicant->contact['phone']['showPhone']) && $applicant->contact['phone']['showPhone'])
                <span>{{ $applicant->contact['phone']['phone'] }}</span>
            @endif
            <span>{{ $applicant->contact['city'] }}{{ !empty($applicant->contact['zone']) ? ', ' . $applicant->contact['zone'] : '' }}</span>
        </div>
    </div>
</header>

<div class="main-content">
    <div class="primary-content">
        <!-- Summary -->
        <section class="section">
            <h2 class="section-title">Professional Summary</h2>
            <p>{{ $applicant->summary }}</p>
        </section>

        <!-- Experience -->
        <section class="section">
            <h2 class="section-title">Professional Experience</h2>
            @foreach($applicant->employment ?? [] as $job)
                <div class="experience-item">
                    <div class="experience-header">
                        <div>
                            <span class="experience-title">{{ $job['title'] }}</span> at
                            <span class="experience-company">{{ $job['employer'] }}</span>
                        </div>
                        <div class="experience-date">
                            {{ $job['duration'][0] }} - {{ $job['duration'][1] ?? 'Present' }}
                        </div>
                    </div>
                    @if(!empty($job['responsibilities']))
                        <ul class="responsibilities">
                            @foreach($job['responsibilities'] as $responsibility)
                                <li>{{ $responsibility }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endforeach
        </section>

        <!-- Education -->
        <section class="section">
            <h2 class="section-title">Education</h2>
            @foreach($applicant->education ?? [] as $education)
                <div class="experience-item">
                    <div class="experience-header">
                        <div>
                            <span class="experience-title">{{ $education['degree'] }}</span>
                            <span class="experience-company">{{ $education['institute'] }}</span>
                        </div>
                        <div class="experience-date">
                            {{ $education['duration'][0] }} - {{ $education['duration'][1] ?? 'Present' }}
                        </div>
                    </div>
                </div>
            @endforeach
        </section>
    </div>

    <div class="secondary-content">
        <!-- Skills -->
        <section class="section">
            <h2 class="section-title">Skills</h2>
            @foreach($applicant->tools ?? [] as $tool)
                <div class="skill-item">
                    <span>{{ $tool['item'] }}</span>
                    <span class="skill-level">
                            @switch($tool['rating'])
                            @case(1) Basic @break
                            @case(2) Intermediate @break
                            @case(3) Good @break
                            @case(4) Very Good @break
                            @case(5) Excellent @break
                            @default N/A
                        @endswitch
                        </span>
                </div>
            @endforeach
        </section>

        <!-- Languages -->
        <section class="section">
            <h2 class="section-title">Languages</h2>
            @foreach($applicant->languages ?? [] as $language)
                @if(!empty($language['item']))
                    <div class="language-item">
                        <span>{{ $language['item'] }}</span>
                        <span class="language-level">
                                @switch($language['rating'])
                                @case(1) Basic @break
                                @case(2) Intermediate @break
                                @case(3) Good @break
                                @case(4) Very Good @break
                                @case(5) Excellent @break
                                @default N/A
                            @endswitch
                            </span>
                    </div>
                @endif
            @endforeach
        </section>

        <!-- Courses -->
        <section class="section">
            <h2 class="section-title">Certifications</h2>
            @foreach($applicant->courses ?? [] as $course)
                @if(!empty($course['title']))
                    <div class="experience-item">
                        <div>
                            <span class="experience-title">{{ $course['title'] }}</span>
                            <div class="experience-date">{{ $course['entity'] }}</div>
                            <div class="experience-date">Duration: {{ $course['duration'] }}</div>
                        </div>
                    </div>
                @endif
            @endforeach
        </section>
    </div>
</div>
</body>
</html>
