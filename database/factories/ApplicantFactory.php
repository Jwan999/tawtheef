<?php

namespace Database\Factories;

use App\Models\Applicant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ApplicantFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Applicant::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

//        $table->increments('id');
//        $table->string('image')->nullable();
//        $table->string('speciality_title')->nullable();
//        $table->json('speciality_children')->nullable();
//        $table->json('education')->nullable();
//        $table->json('languages')->nullable();
//        $table->json('skills')->nullable();
//        $table->json('tools')->nullable();
//        $table->boolean('work_availability')->nullable();
//        $table->string('full_name')->nullable();
//        $table->text('summary')->nullable();
//        $table->json('courses')->nullable();
//        $table->string('phone')->nullable();
//        $table->string('gender')->nullable();
//        $table->string('email')->nullable();
//        $table->json('links')->nullable();
//        $table->string('birthdate')->nullable();
//        $table->string('city')->nullable();
//        $table->string('zone')->nullable();
//        $table->json('employment')->nullable();
//        $table->json('activities')->nullable();
//        $table->integer('profileable_id')->unsigned()->nullable();
//        $table->string('profileable_type')->nullable();
//        $table->timestamps();
        return [
            'image' => $this->faker->image(storage_path('images'), 50, 50),
            'speciality_title' => $this->faker->randomElement(['Creative & Design', 'Development', 'Business & Management', 'Writing & Editing', 'Science & Engineering', 'Other']),
            'speciality_children' => $this->generateRandomSpecialityChildren(), // Implement this function to generate random children
            // Define other fields and their random generation logic
        ];
    }

    /**
     * Generate random speciality children.
     *
     * @return array
     */
    private function generateRandomSpecialityChildren()
    {
        $children = [
            ['Graphic Design', 'User Experience (UX) Design', 'User Interface (UI) Design', 'Visual Design', 'Motion Graphics', 'Interaction Design', 'Product Design', 'Content Design', 'Fashion Design', 'Interior Design', 'Architecture'],
            ['Front-End Development', 'Back-End Development', 'Full-Stack Development', 'Web Development', 'Mobile Development', 'UI/UX Design', 'Software Development', 'Data Science', 'Machine Learning', 'Artificial Intelligence', 'DevOps', 'Cloud Computing'],
            ['Marketing', 'Sales', 'Project Management', 'Operations Management'],
            ['Copywriting', 'Content Writing', 'Technical Writing', 'Editing', 'Proofreading'],
            ['Biology', 'Chemistry', 'Engineering (various specializations)', 'Mathematics', 'Statistics', 'Physics'],
            ['Education & Training', 'Healthcare', 'Law', 'Social Work', 'Communications', 'Public Relations', 'Customer Service', 'Translation & Interpretation', 'Undecided yet'],

        ];
        $randomTitleChildren = $children[array_rand($children)];
        return json_encode($randomTitleChildren);
    }

    // Define other custom random data generation methods as needed
}
