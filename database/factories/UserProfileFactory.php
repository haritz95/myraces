<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UserProfile>
 */
class UserProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'username' => fake()->unique()->userName(),
            'city' => fake()->city(),
            'country' => fake()->countryCode(),
            'bio' => fake()->optional()->sentence(),
            'gender' => fake()->randomElement(['male', 'female', 'other']),
            'birth_date' => fake()->dateTimeBetween('-60 years', '-18 years')->format('Y-m-d'),
            'is_public' => true,
            'locale' => fake()->randomElement(['es', 'en']),
        ];
    }
}
