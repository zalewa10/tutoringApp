<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\Lesson;
use App\Models\Payment;
use App\Models\User; // added

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Use existing user or create a single demo user (only one user in DB from this seeder)
        $user = User::first() ?: User::factory()->create([
            'name' => 'Demo User',
            'email' => 'demo@example.com',
            'password' => 'dupa',
        ]);

        // Create students for that single user
        $students = Student::factory()->count(5)->for($user)->create();

        foreach ($students as $student) {
            $lessonsCount = rand(1, 3);
            for ($i = 0; $i < $lessonsCount; $i++) {
                $start = now()->addDays(rand(-30, 30))->setTime(rand(8, 18), [0, 15, 30, 45][array_rand([0,1,2,3])]);
                $end = (clone $start)->addHour();
                $lesson = $student->lessons()->create([
                    'title' => 'Lekcja z ' . $student->name,
                    'user_id' => $student->user_id,
                    'start' => $start,
                    'end' => $end,
                    'notes' => null,
                ]);

                // random payment for this lesson
                $statuses = ['awaiting','paid','overdue'];
                $status = $statuses[array_rand($statuses)];
                Payment::create([
                    'lesson_id' => $lesson->id,
                    'user_id' => $student->user_id,
                    'amount' => rand(30,120),
                    'status' => $status,
                    'paid_at' => $status === 'paid' ? now()->subDays(rand(0,10)) : null,
                    'notes' => null,
                ]);
            }
        }
    }
}
