<?php

namespace Database\Factories;

use App\Models\PersonalRecord;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PersonalRecord>
 */
class PersonalRecordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $distances = [
            ['label' => '5K', 'km' => 5.0, 'min_seconds' => 1080, 'max_seconds' => 1800],
            ['label' => '10K', 'km' => 10.0, 'min_seconds' => 2400, 'max_seconds' => 3600],
            ['label' => 'Half Marathon', 'km' => 21.098, 'min_seconds' => 5400, 'max_seconds' => 9000],
            ['label' => 'Marathon', 'km' => 42.195, 'min_seconds' => 10800, 'max_seconds' => 21600],
        ];

        $distance = fake()->randomElement($distances);

        return [
            'user_id' => User::factory(),
            'race_id' => null,
            'distance_label' => $distance['label'],
            'distance_km' => $distance['km'],
            'time_seconds' => fake()->numberBetween($distance['min_seconds'], $distance['max_seconds']),
            'date' => fake()->dateTimeBetween('-3 years', 'now')->format('Y-m-d'),
            'location' => fake()->optional()->city(),
        ];
    }

    public function forUser(User $user): static
    {
        return $this->state(['user_id' => $user->id]);
    }
}
