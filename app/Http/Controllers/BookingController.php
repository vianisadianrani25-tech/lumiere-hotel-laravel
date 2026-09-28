<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function create(Room $room): View
    {
        return view('booking.create', [
            'room' => $room,
            'rooms' => Room::orderBy('price')->get(),
        ]);
    }

    public function store(StoreBookingRequest $request): RedirectResponse
    {
        $room = Room::findOrFail($request->integer('room_id'));

        $checkIn = Carbon::parse($request->string('check_in'));
        $checkOut = Carbon::parse($request->string('check_out'));
        $nights = $checkIn->diffInDays($checkOut);

        $booking = Booking::create([
            'user_id' => $request->user()->id,
            'room_id' => $room->id,
            'booking_number' => 'LMR-'.strtoupper(Str::random(6)),
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
            'payment_status' => 'pending',
        ]);

        return redirect()->route('payment.show', $booking);
    }

    public function confirmation(Booking $booking): View
    {
        abort_if($booking->user_id !== auth()->id(), 403);
        abort_unless($booking->payment_status === 'paid', 404);

        return view('booking.confirmation', compact('booking'));
    }
}
