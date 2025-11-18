<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User; // added

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    protected $model = \App\Models\Student::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'surname' => fake()->lastName(),
            'tel' => fake()->phoneNumber(),
            'rate' => fake()->randomFloat(2, 0, 100),
            'active' => fake()->boolean(),
            // user_id intentionally omitted — set by seeder or caller
        ];
    }
}
