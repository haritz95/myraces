<?php

namespace Database\Factories;

use App\Models\Race;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Race>
 */
class RaceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $distances = [5, 10, 21.097, 42.195];
        $distance = fake()->randomElement($distances);
        $status = fake()->randomElement(['upcoming', 'completed', 'completed', 'completed', 'dnf']);

        $finishTime = null;
        if ($status === 'completed') {
            $paceSecondsPerKm = fake()->numberBetween(240, 480);
            $finishTime = (int) ($paceSecondsPerKm * $distance);
        }

        return [
            'user_id' => User::factory(),
            'name' => fake()->city().' '.fake()->randomElement(['10K', 'Media Maratón', 'Maratón', 'Trail Run']),
            'date' => fake()->dateTimeBetween('-1 year', '+6 months')->format('Y-m-d'),
            'location' => fake()->city(),
            'country' => fake()->countryCode(),
            'distance' => $distance,
            'distance_unit' => 'km',
            'modality' => fake()->randomElement(['road', 'trail', 'mountain', 'road', 'road']),
            'status' => $status,
            'finish_time' => $finishTime,
            'position_overall' => $status === 'completed' ? fake()->optional()->numberBetween(1, 500) : null,
            'position_category' => $status === 'completed' ? fake()->optional()->numberBetween(1, 100) : null,
            'bib_number' => fake()->optional()->numerify('###'),
            'cost' => fake()->optional()->randomFloat(2, 10, 120),
            'is_public' => true,
        ];
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'finish_time' => fake()->numberBetween(1200, 18000),
        ]);
    }

    public function upcoming(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'upcoming',
            'date' => fake()->dateTimeBetween('+1 day', '+6 months')->format('Y-m-d'),
            'finish_time' => null,
        ]);
    }
}
