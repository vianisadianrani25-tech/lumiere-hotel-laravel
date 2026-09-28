<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $checkIn = fake()->dateTimeBetween('+1 day', '+2 weeks');
        $nights = fake()->numberBetween(1, 5);
        $checkOut = (clone $checkIn)->modify("+{$nights} days");
        $roomPrice = fake()->numberBetween(8, 30) * 100000;

        return [
            'user_id' => User::factory(),
            'room_id' => Room::factory(),
            'booking_number' => 'LMR-'.fake()->unique()->numerify('######'),
            'guest_name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'check_in' => $checkIn,
            'check_out' => $checkOut,
            'guests' => fake()->numberBetween(1, 4),
            'nights' => $nights,
            'room_price' => $roomPrice,
            'total' => $roomPrice * $nights,
            'special_request' => fake()->optional()->sentence(),
            'payment_method' => null,
            'payment_status' => 'pending',
            'paid_at' => null,
        ];
    }
}
