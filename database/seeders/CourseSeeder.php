<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        Course::create([
            'title' => 'Laravel From Zero To Hero',
            'description' => 'Complete Laravel course for beginners',
            'price' => 199.99,
            'is_published' => true
        ]);

        Course::create([
            'title' => 'Advanced PHP OOP',
            'description' => 'Deep dive into OOP concepts',
            'price' => 149.99,
            'is_published' => true
        ]);
    }
}
