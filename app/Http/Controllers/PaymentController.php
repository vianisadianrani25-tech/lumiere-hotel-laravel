<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProcessPaymentRequest;
use App\Models\Booking;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function show(Booking $booking): View
    {
        abort_if($booking->user_id !== auth()->id(), 403);
        abort_unless($booking->payment_status === 'pending', 404);

        return view('payment.show', compact('booking'));
    }

    public function process(ProcessPaymentRequest $request, Booking $booking): RedirectResponse
    {
        abort_if($booking->user_id !== auth()->id(), 403);
        abort_unless($booking->payment_status === 'pending', 404);

        $method = $request->string('payment_method');
        $methodLabel = match ((string) $method) {
            'bank' => 'Bank Transfer',
            'ewallet' => 'E-Wallet ('.$request->string('ewallet_type').')',
            'card' => 'Credit / Debit Card',
            'hotel' => 'Pay At Hotel',
            default => (string) $method,
        };

        $booking->update([
            'payment_method' => $methodLabel,
            'payment_status' => 'paid',
            'paid_at' => now(),
        ]);

        return redirect()->route('booking.confirmation', $booking);
    }
}
