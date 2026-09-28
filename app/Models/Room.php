<?php

namespace App\Models;

use Database\Factories\RoomFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

#[Fillable(['name', 'slug', 'category', 'badge', 'price', 'capacity', 'rating', 'image_url', 'description'])]
class Room extends Model
{
    /** @use HasFactory<RoomFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'rating' => 'decimal:1',
            'capacity' => 'integer',
        ];
    }

    /**
     * Public URL of the room image, supporting both external URLs and uploaded files on the public disk.
     *
     * @return Attribute<string, never>
     */
    protected function imageSrc(): Attribute
    {
        return Attribute::get(fn (): string => Str::startsWith((string) $this->image_url, ['http://', 'https://'])
            ? $this->image_url
            : asset('storage/'.$this->image_url));
    }

    /**
     * Whether the image is an uploaded file stored on the public disk.
     */
    public function hasUploadedImage(): bool
    {
        return filled($this->image_url) && ! Str::startsWith($this->image_url, ['http://', 'https://']);
    }

    /**
     * @return HasMany<Booking, $this>
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
