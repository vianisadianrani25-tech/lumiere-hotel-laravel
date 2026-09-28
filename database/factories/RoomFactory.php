<?php

namespace Database\Factories;

use App\Models\Room;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Room>
 */
class RoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->randomElement(['Deluxe Room', 'Executive Suite', 'Presidential Villa', 'Garden Room', 'Ocean Suite']);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'category' => fake()->word(),
            'badge' => null,
            'price' => fake()->numberBetween(8, 30) * 100000,
            'capacity' => fake()->numberBetween(2, 4),
            'rating' => fake()->randomFloat(1, 4.5, 5.0),
            'image_url' => 'https://images.unsplash.com/photo-1590490360182-c33d57733427?auto=format&fit=crop&w=1000&q=85',
            'description' => fake()->sentence(),
        ];
    }
}
