<?php

namespace Database\Factories;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Expense>
 */
class ExpenseFactory extends Factory
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
            'race_id' => null,
            'amount' => fake()->randomFloat(2, 5, 250),
            'currency' => fake()->randomElement(['EUR', 'USD', 'GBP']),
            'category' => fake()->randomElement(['registration', 'travel', 'accommodation', 'gear', 'nutrition', 'other']),
            'description' => fake()->optional()->sentence(4),
            'date' => fake()->dateTimeBetween('-2 years', 'now')->format('Y-m-d'),
        ];
    }

    public function forUser(User $user): static
    {
        return $this->state(['user_id' => $user->id]);
    }

    public function registration(): static
    {
        return $this->state(['category' => 'registration']);
    }
}
