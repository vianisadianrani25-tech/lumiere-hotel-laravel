<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rooms = [
            [
                'name' => 'Deluxe Room',
                'slug' => 'deluxe-room',
                'category' => 'DELUXE',
                'badge' => 'POPULAR',
                'price' => 850000,
                'capacity' => 2,
                'rating' => 4.9,
                'image_url' => 'https://images.unsplash.com/photo-1590490360182-c33d57733427?auto=format&fit=crop&w=1000&q=85',
                'description' => 'Elegant room with a comfortable king bed and beautiful city view.',
            ],
            [
                'name' => 'Executive Suite',
                'slug' => 'executive-suite',
                'category' => 'EXECUTIVE',
                'badge' => null,
                'price' => 1450000,
                'capacity' => 3,
                'rating' => 4.8,
                'image_url' => 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=1000&q=85',
                'description' => 'Spacious suite with a separate living area and premium amenities.',
            ],
            [
                'name' => 'Presidential Villa',
                'slug' => 'presidential-villa',
                'category' => 'PREMIUM',
                'badge' => 'LUXURY',
                'price' => 2850000,
                'capacity' => 4,
                'rating' => 5.0,
                'image_url' => 'https://images.unsplash.com/photo-1600607687920-4e2a09cf159d?auto=format&fit=crop&w=1000&q=85',
                'description' => 'Private luxury villa with elegant interiors and a relaxing atmosphere.',
            ],
        ];

        foreach ($rooms as $room) {
            Room::updateOrCreate(['slug' => $room['slug']], $room);
        }
    }
}
