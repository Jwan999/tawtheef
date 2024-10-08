<?php

namespace App\Console\Commands;

use App\Models\FormControl;
use Illuminate\Console\Command;

class GenerateFieldSelects extends Command
{
    protected $signature = 'generate:selects';
    protected $description = 'Generate field selects data';

    protected $formControl;

    public function __construct(FormControl $formControl)
    {
        parent::__construct();
        $this->formControl = $formControl;
    }

    public function handle()
    {
        $data = [
            [
                'key' => 'specialities',
                'value' => json_encode([
                    [
                        'title' => 'Creative & Design',
                        'children' => ['Graphic Design', 'User Experience (UX) Design', 'User Interface (UI) Design', 'Visual Design', 'Motion Graphics', 'Interaction Design', 'Product Design', 'Content Design', 'Fashion Design', 'Interior Design', 'Architecture']
                    ],
                    [
                        'title' => 'Development',
                        'children' => ['Front-End Development', 'Back-End Development', 'Full-Stack Development', 'Web Development', 'Mobile Development', 'UI/UX Design', 'Software Development', 'Data Science', 'Machine Learning', 'Artificial Intelligence', 'DevOps', 'Cloud Computing']
                    ],
                    [
                        'title' => 'Business & Management',
                        'children' => ['Marketing', 'Sales', 'Project Management', 'Operations Management']
                    ],
                    [
                        'title' => 'Writing & Editing',
                        'children' => ['Copywriting', 'Content Writing', 'Technical Writing', 'Editing', 'Proofreading']
                    ],
                    [
                        'title' => 'Science & Engineering',
                        'children' => ['Biology', 'Chemistry', 'Engineering', 'Mathematics', 'Statistics', 'Physics']
                    ],
                    [
                        'title' => 'Other',
                        'children' => ['Education & Training', 'Healthcare', 'Law', 'Social Work', 'Communications', 'Public Relations', 'Customer Service', 'Translation & Interpretation', 'Undecided yet']
                    ],
                ]),
            ],
            [
                'key' => 'keywords',
                'value' => json_encode(["Python", "Photoshop", "Frontend Developer", "UX Designer", "Project Management", "Client Relations"]),
            ],
            [
                'key' => 'degrees',
                'value' => json_encode(["Bachelor's Degree", "Master's Degree", "Doctorate (Ph.D.)", "High School", "Diploma", "Undergraduate", "Minor Degree"]),
            ],
            [
                'key' => 'languages',
                'value' => json_encode(['Arabic', 'Kurdish', 'English', 'Turkish', 'Persian', 'French', 'German']),
            ],
            [
                'key' => 'skills',
                'value' => json_encode(['Communication Skills', 'Teamwork', 'Problem Solving', 'Time Management', 'Adaptability', 'Leadership', 'Creativity', 'Critical Thinking', 'Interpersonal Skills', 'Stress Management', 'Negotiation Skills', 'Decision Making', 'Conflict Resolution', 'Customer Service', 'Emotional Intelligence', 'Networking', 'Presentation Skills', 'Resilience', 'Flexibility', 'Attention to Detail']),
            ],
            [
                'key' => 'participation',
                'value' => json_encode(['Organizer', 'Co-organizer', 'Volunteer', 'Speaker', 'Trainer', 'Participant', 'Attendee', 'Facilitator', 'Moderator', 'Panelist', 'Judge', 'Mentor', 'Coach', 'Presenter', 'Host', 'Guest', 'Support Staff']),
            ],
            [
                'key' => 'cities',
                'value' => json_encode(['Baghdad', 'Basra', 'Mosul', 'Erbil', 'Kirkuk', 'Najaf', 'Karbala', 'Nasiriyah', 'Sulaymaniyah', 'Al Anbar', 'Fallujah', 'Diyala', 'Babil', 'Saladin', 'Samarra', 'Wasit', 'Maysan','Diyala']),
            ],
        ];

        $insertedCount = 0;
        $skippedCount = 0;

        foreach ($data as $record) {
            $existingRecord = FormControl::where('key', $record['key'])->first();

            if (!$existingRecord) {
                FormControl::create($record);
                $insertedCount++;
            } else {
                $skippedCount++;
            }
        }

        $this->info("Field selects data operation completed.");
        $this->info("Inserted records: $insertedCount");
        $this->info("Skipped existing records: $skippedCount");
    }
}
