<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lesson>
 */
class LessonFactory extends Factory
{
    protected $model = \App\Models\Lesson::class;

    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween('-1 month', '+1 month');
        $end = (clone $start)->modify('+1 hour');

        return [
            'title' => 'Lekcja',
            'start' => $start,
            'end' => $end,
            'notes' => $this->faker->optional()->sentence(),
            // student_id and user_id usually set by seeding context
        ];
    }
}
