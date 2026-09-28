<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class MyBookingController extends Controller
{
    public function index(): View
    {
        $bookings = auth()->user()
            ->bookings()
            ->with('room')
            ->latest()
            ->get();

        return view('my-bookings.index', compact('bookings'));
    }

    public function edit(Booking $booking): View
    {
        abort_if($booking->user_id !== auth()->id(), 403);
        abort_unless($booking->payment_status === 'pending', 404);

        return view('booking.create', [
            'booking' => $booking,
            'room' => $booking->room,
            'rooms' => Room::orderBy('price')->get(),
        ]);
    }

    public function update(StoreBookingRequest $request, Booking $booking): RedirectResponse
    {
        abort_if($booking->user_id !== auth()->id(), 403);
        abort_unless($booking->payment_status === 'pending', 404);

        $room = Room::findOrFail($request->integer('room_id'));

        $checkIn = Carbon::parse($request->string('check_in'));
        $checkOut = Carbon::parse($request->string('check_out'));
        $nights = $checkIn->diffInDays($checkOut);

        $booking->update([
            'room_id' => $room->id,
            'guest_name' => $request->string('guest_name'),
            'email' => $request->string('email'),
            'phone' => $request->string('phone'),
            'check_in' => $checkIn,
            'check_out' => $checkOut,
            'guests' => $request->integer('guests'),
            'nights' => $nights,
            'room_price' => $room->price,
            'total' => $nights * $room->price,
            'special_request' => $request->string('special_request'),
        ]);

        return redirect()->route('my-bookings.index')->with('status', 'Reservasi berhasil diperbarui.');
    }

    public function cancel(Booking $booking): RedirectResponse
    {
        abort_if($booking->user_id !== auth()->id(), 403);
        abort_unless($booking->payment_status === 'pending', 404);

        $booking->update(['payment_status' => 'cancelled']);

        return redirect()->route('my-bookings.index')->with('status', 'Reservasi berhasil dibatalkan.');
    }

    public function destroy(Booking $booking): RedirectResponse
    {
        abort_if($booking->user_id !== auth()->id(), 403);
        abort_if($booking->payment_status === 'paid', 404);

        $booking->delete();

        return redirect()->route('my-bookings.index')->with('status', 'Reservasi berhasil dihapus.');
    }
}
