<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\User;

class CourseSeeder extends Seeder
{
    public function run()
    {
        $courses = [
            ['name' => 'Computer Science', 'code' => 'CS101', 'description' => 'Basics of CS'],
            ['name' => 'Mathematics', 'code' => 'MATH201', 'description' => 'Advanced Calculus'],
            ['name' => 'Physics', 'code' => 'PHY101', 'description' => 'Mechanics and Heat'],
        ];

        foreach ($courses as $c) {
            $course = Course::create($c);
            
            // Enroll student to each course for testing
            $student = User::where('role', 'student')->first();
            if ($student) {
                $course->students()->attach($student->id);
            }
        }
    }
}
