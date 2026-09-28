<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RoomManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
    }

    /**
     * @return array<string, mixed>
     */
    private function validPayload(array $overrides = []): array
    {
        return [
            'name' => 'Garden Room',
            'category' => 'GARDEN',
            'badge' => 'NEW',
            'price' => 950000,
            'capacity' => 2,
            'rating' => 4.7,
            'image' => UploadedFile::fake()->image('garden-room.jpg'),
            'description' => 'Cozy room facing the garden.',
            ...$overrides,
        ];
    }

    public function test_guests_cannot_access_room_management(): void
    {
        $this->get(route('rooms.index'))->assertRedirect(route('login'));
    }

    public function test_room_index_and_forms_can_be_rendered(): void
    {
        $user = User::factory()->create();
        $room = Room::factory()->create();

        $this->actingAs($user)->get(route('rooms.index'))->assertOk()->assertSee($room->name);
        $this->actingAs($user)->get(route('rooms.create'))->assertOk();
        $this->actingAs($user)->get(route('rooms.edit', $room))->assertOk()->assertSee($room->name);
    }

    public function test_room_can_be_created_with_generated_slug(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('rooms.store'), $this->validPayload())
            ->assertRedirect(route('rooms.index'));

        $this->assertDatabaseHas('rooms', [
            'name' => 'Garden Room',
            'slug' => 'garden-room',
            'capacity' => 2,
        ]);

        $room = Room::firstWhere('slug', 'garden-room');
        $this->assertStringStartsWith('rooms/', $room->image_url);
        Storage::disk('public')->assertExists($room->image_url);
        $this->assertSame(asset('storage/'.$room->image_url), $room->image_src);
    }

    public function test_room_image_is_required_when_creating(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('rooms.store'), $this->validPayload(['image' => null]))
            ->assertSessionHasErrors('image');

        $this->assertDatabaseCount('rooms', 0);
    }

    public function test_room_image_must_be_jpeg_png_or_jpg(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('rooms.store'), $this->validPayload(['image' => UploadedFile::fake()->image('room.gif')]))
            ->assertSessionHasErrors('image');

        $this->actingAs($user)
            ->post(route('rooms.store'), $this->validPayload(['image' => UploadedFile::fake()->create('room.pdf', 100, 'application/pdf')]))
            ->assertSessionHasErrors('image');

        $this->assertDatabaseCount('rooms', 0);
    }

    public function test_room_image_cannot_exceed_two_megabytes(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('rooms.store'), $this->validPayload(['image' => UploadedFile::fake()->image('room.png')->size(3000)]))
            ->assertSessionHasErrors('image');

        $this->assertDatabaseCount('rooms', 0);
    }

    public function test_room_can_be_updated_without_changing_image(): void
    {
        $user = User::factory()->create();
        $room = Room::factory()->create(['name' => 'Garden Room', 'slug' => 'garden-room']);
        $originalImage = $room->image_url;

        $this->actingAs($user)
            ->put(route('rooms.update', $room), $this->validPayload(['image' => null, 'capacity' => 3]))
            ->assertRedirect(route('rooms.index'));

        $this->assertSame(3, $room->fresh()->capacity);
        $this->assertSame($originalImage, $room->fresh()->image_url);
    }

    public function test_uploading_new_image_replaces_old_uploaded_image(): void
    {
        $user = User::factory()->create();
        $oldPath = UploadedFile::fake()->image('old.jpg')->store('rooms', 'public');
        $room = Room::factory()->create(['name' => 'Garden Room', 'slug' => 'garden-room', 'image_url' => $oldPath]);

        $this->actingAs($user)
            ->put(route('rooms.update', $room), $this->validPayload(['image' => UploadedFile::fake()->image('new.png')]))
            ->assertRedirect(route('rooms.index'));

        Storage::disk('public')->assertMissing($oldPath);
        Storage::disk('public')->assertExists($room->fresh()->image_url);
        $this->assertNotSame($oldPath, $room->fresh()->image_url);
    }

    public function test_external_image_url_is_still_displayed(): void
    {
        $room = Room::factory()->make(['image_url' => 'https://images.unsplash.com/photo-1']);

        $this->assertSame('https://images.unsplash.com/photo-1', $room->image_src);
        $this->assertFalse($room->hasUploadedImage());
    }

    public function test_room_name_must_be_unique(): void
    {
        $user = User::factory()->create();
        Room::factory()->create(['name' => 'Garden Room', 'slug' => 'garden-room']);

        $this->actingAs($user)
            ->post(route('rooms.store'), $this->validPayload())
            ->assertSessionHasErrors('slug');

        $this->assertDatabaseCount('rooms', 1);
    }

    public function test_room_can_be_updated(): void
    {
        $user = User::factory()->create();
        $room = Room::factory()->create(['name' => 'Garden Room', 'slug' => 'garden-room']);

        $this->actingAs($user)
            ->put(route('rooms.update', $room), $this->validPayload(['price' => 1200000]))
            ->assertRedirect(route('rooms.index'));

        $this->assertSame('1200000.00', $room->fresh()->price);
        $this->assertSame('garden-room', $room->fresh()->slug);
    }

    public function test_room_without_bookings_can_be_deleted(): void
    {
        $user = User::factory()->create();
        $imagePath = UploadedFile::fake()->image('room.jpg')->store('rooms', 'public');
        $room = Room::factory()->create(['image_url' => $imagePath]);

        $this->actingAs($user)
            ->delete(route('rooms.destroy', $room))
            ->assertRedirect(route('rooms.index'));

        $this->assertModelMissing($room);
        Storage::disk('public')->assertMissing($imagePath);
    }

    public function test_room_with_bookings_cannot_be_deleted(): void
    {
        $user = User::factory()->create();
        $room = Room::factory()->create();
        Booking::factory()->for($room)->create();

        $this->actingAs($user)
            ->delete(route('rooms.destroy', $room))
            ->assertRedirect(route('rooms.index'))
            ->assertSessionHas('error');

        $this->assertModelExists($room);
    }
}
