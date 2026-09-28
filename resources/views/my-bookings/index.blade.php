<x-hotel-layout active="my-bookings" body-class="my-booking-page" title="My Booking">

<section class="booking-page-header">
    <span>RESERVATION</span>
    <h1>My Booking</h1>
    <p>Manage and view your hotel reservation.</p>
</section>

<main class="my-booking-container">

    @if (session('status'))
        <p class="confirmation-note">{{ session('status') }}</p>
    @endif

    @forelse ($bookings as $booking)

        <section class="my-booking-card">

            <div class="booking-card-top">
                <div>
                    <span class="booking-status-label">RESERVATION</span>
                    <h2>{{ $booking->room->name }}</h2>
                </div>

                <div class="booking-status @if($booking->payment_status === 'cancelled') cancelled @endif">
                    @if ($booking->payment_status === 'paid') ✓ PAID
                    @elseif ($booking->payment_status === 'cancelled') ✕ CANCELLED
                    @else ⏳ PENDING
                    @endif
                </div>
            </div>

            <div class="my-booking-number">
                <span>BOOKING NUMBER</span>
                <strong>{{ $booking->booking_number }}</strong>
            </div>

            <div class="booking-date-section">
                <div class="booking-date">
                    <span>CHECK IN</span>
                    <strong>{{ $booking->check_in->format('Y-m-d') }}</strong>
                </div>

                <div class="booking-arrow">→</div>

                <div class="booking-date">
                    <span>CHECK OUT</span>
                    <strong>{{ $booking->check_out->format('Y-m-d') }}</strong>
                </div>
            </div>

            <div class="my-booking-details">
                <div><span>GUEST NAME</span><strong>{{ $booking->guest_name }}</strong></div>
                <div><span>GUESTS</span><strong>{{ $booking->guests }}</strong></div>
                <div><span>NIGHTS</span><strong>{{ $booking->nights }}</strong></div>
                <div><span>PAYMENT</span><strong>{{ $booking->payment_method ?? '—' }}</strong></div>
            </div>

            <div class="my-booking-total">
                <span>TOTAL {{ $booking->payment_status === 'paid' ? 'PAID' : '' }}</span>
                <strong>Rp {{ number_format($booking->grand_total, 0, ',', '.') }}</strong>
            </div>

            <div class="my-booking-actions">

                @if ($booking->payment_status === 'pending')
                    <a href="{{ route('my-bookings.edit', $booking) }}" class="print-booking-button edit-booking-button">EDIT RESERVATION</a>

                    <form method="POST" action="{{ route('my-bookings.cancel', $booking) }}" onsubmit="return confirm('Are you sure you want to cancel this reservation?');">
                        @csrf
                        <button type="submit" class="cancel-button">CANCEL RESERVATION</button>
                    </form>
                @endif

                @if ($booking->payment_status !== 'paid')
                    <form method="POST" action="{{ route('my-bookings.destroy', $booking) }}" onsubmit="return confirm('Are you sure you want to delete this reservation permanently?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="cancel-button">DELETE</button>
                    </form>
                @endif

                <button onclick="window.print()" class="print-booking-button">PRINT DETAILS</button>

            </div>

        </section>

    @empty

        <section class="no-booking">
            <div class="no-booking-icon">✦</div>
            <span>NO RESERVATION</span>
            <h2>You Don't Have Any Booking Yet.</h2>
            <p>Your confirmed reservations will appear here.</p>
            <a href="{{ route('home') }}#rooms" class="book-room-button">EXPLORE ROOMS →</a>
        </section>

    @endforelse

</main>

</x-hotel-layout>
