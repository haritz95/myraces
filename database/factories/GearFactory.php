<?php

namespace Database\Factories;

use App\Models\Gear;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Gear>
 */
class GearFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $shoesBrands = ['Nike', 'Adidas', 'ASICS', 'Brooks', 'Hoka', 'Saucony', 'New Balance', 'Salomon'];
        $shoeModels = ['Vaporfly', 'Adizero', 'Gel-Nimbus', 'Ghost', 'Clifton', 'Endorphin Pro', 'Fresh Foam', 'Speedcross'];

        $currentKm = fake()->randomFloat(2, 0, 800);
        $maxKm = fake()->randomElement([500, 700, 800, 1000, null]);

        return [
            'user_id' => User::factory(),
            'brand' => fake()->randomElement($shoesBrands),
            'model' => fake()->randomElement($shoeModels),
            'type' => fake()->randomElement(['shoes', 'watch', 'clothing', 'accessories', 'nutrition', 'other']),
            'purchase_date' => fake()->optional()->dateTimeBetween('-3 years', 'now')?->format('Y-m-d'),
            'current_km' => $currentKm,
            'max_km' => $maxKm,
            'purchase_price' => fake()->optional()->randomFloat(2, 50, 350),
            'is_active' => fake()->boolean(80),
            'notes' => fake()->optional()->sentence(),
        ];
    }

    public function forUser(User $user): static
    {
        return $this->state(['user_id' => $user->id]);
    }

    public function shoes(): static
    {
        return $this->state(['type' => 'shoes']);
    }
}
