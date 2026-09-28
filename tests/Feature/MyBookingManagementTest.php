<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MyBookingManagementTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, mixed>
     */
    private function validPayload(Room $room, array $overrides = []): array
    {
        return [
            'room_id' => $room->id,
            'check_in' => now()->addDays(3)->toDateString(),
            'check_out' => now()->addDays(6)->toDateString(),
            'guests' => 2,
            'guest_name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'phone' => '08123456789',
            'special_request' => 'Late check in',
            ...$overrides,
        ];
    }

    public function test_owner_can_view_edit_form_for_pending_booking(): void
    {
        $booking = Booking::factory()->create();

        $this->actingAs($booking->user)
            ->get(route('my-bookings.edit', $booking))
            ->assertOk()
            ->assertSee($booking->booking_number);
    }

    public function test_owner_can_update_pending_booking_and_total_is_recalculated(): void
    {
        $booking = Booking::factory()->create();
        $newRoom = Room::factory()->create(['price' => 1000000, 'capacity' => 3]);

        $this->actingAs($booking->user)
            ->put(route('my-bookings.update', $booking), $this->validPayload($newRoom))
            ->assertRedirect(route('my-bookings.index'));

        $booking->refresh();

        $this->assertSame($newRoom->id, $booking->room_id);
        $this->assertSame(3, $booking->nights);
        $this->assertSame('3000000.00', $booking->total);
        $this->assertSame('Budi Santoso', $booking->guest_name);
        $this->assertSame('pending', $booking->payment_status);
    }

    public function test_update_rejects_guests_over_room_capacity(): void
    {
        $booking = Booking::factory()->create();
        $room = Room::factory()->create(['capacity' => 2]);

        $this->actingAs($booking->user)
            ->put(route('my-bookings.update', $booking), $this->validPayload($room, ['guests' => 4]))
            ->assertSessionHasErrors('guests');
    }

    public function test_paid_booking_cannot_be_edited(): void
    {
        $booking = Booking::factory()->create(['payment_status' => 'paid']);

        $this->actingAs($booking->user)->get(route('my-bookings.edit', $booking))->assertNotFound();
        $this->actingAs($booking->user)
            ->put(route('my-bookings.update', $booking), $this->validPayload($booking->room))
            ->assertNotFound();
    }

    public function test_other_users_cannot_edit_or_delete_booking(): void
    {
        $booking = Booking::factory()->create();
        $otherUser = User::factory()->create();

        $this->actingAs($otherUser)->get(route('my-bookings.edit', $booking))->assertForbidden();
        $this->actingAs($otherUser)
            ->put(route('my-bookings.update', $booking), $this->validPayload($booking->room))
            ->assertForbidden();
        $this->actingAs($otherUser)->delete(route('my-bookings.destroy', $booking))->assertForbidden();

        $this->assertModelExists($booking);
    }

    public function test_owner_can_delete_pending_or_cancelled_booking(): void
    {
        $pending = Booking::factory()->create();
        $cancelled = Booking::factory()->for($pending->user)->create(['payment_status' => 'cancelled']);

        $this->actingAs($pending->user)
            ->delete(route('my-bookings.destroy', $pending))
            ->assertRedirect(route('my-bookings.index'));
        $this->actingAs($pending->user)
            ->delete(route('my-bookings.destroy', $cancelled))
            ->assertRedirect(route('my-bookings.index'));

        $this->assertModelMissing($pending);
        $this->assertModelMissing($cancelled);
    }

    public function test_paid_booking_cannot_be_deleted(): void
    {
        $booking = Booking::factory()->create(['payment_status' => 'paid']);

        $this->actingAs($booking->user)
            ->delete(route('my-bookings.destroy', $booking))
            ->assertNotFound();

        $this->assertModelExists($booking);
    }
}
