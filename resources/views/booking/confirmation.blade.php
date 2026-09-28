<x-hotel-layout body-class="confirmation-page" title="Confirmation">

<main class="confirmation-container">

    <section class="confirmation-card">

        <div class="success-icon">✓</div>

        <span class="confirmation-label">RESERVASI DIKONFIRMASI</span>

        <h1>Penginapan Anda Telah Dikonfirmasi</h1>

        <p class="confirmation-intro">
            Terima kasih telah memilih Lumière Hotel. Reservasi Anda telah berhasil dicatat.
        </p>

        <div class="booking-number-box">
            <span>BOOKING NUMBER</span>
            <strong id="bookingNumber">{{ $booking->booking_number }}</strong>
        </div>

        <div class="confirmation-details">

            <div class="detail-item">
                <span>GUEST NAME</span>
                <strong id="confirmationName">{{ $booking->guest_name }}</strong>
            </div>

            <div class="detail-item">
                <span>ROOM</span>
                <strong id="confirmationRoom">{{ $booking->room->name }}</strong>
            </div>

            <div class="detail-item">
                <span>CHECK IN</span>
                <strong id="confirmationCheckIn">{{ $booking->check_in->format('Y-m-d') }}</strong>
            </div>

            <div class="detail-item">
                <span>CHECK OUT</span>
                <strong id="confirmationCheckOut">{{ $booking->check_out->format('Y-m-d') }}</strong>
            </div>

            <div class="detail-item">
                <span>GUESTS</span>
                <strong id="confirmationGuests">{{ $booking->guests }}</strong>
            </div>

            <div class="detail-item">
                <span>NUMBER OF NIGHTS</span>
                <strong id="confirmationNights">{{ $booking->nights }}</strong>
            </div>

            @if ($booking->special_request)
                <div class="detail-item special-request-detail">
                    <span>SPECIAL REQUEST</span>
                    <strong id="confirmationSpecialRequest">{{ $booking->special_request }}</strong>
                </div>
            @endif

        </div>

        <div class="confirmation-total">
            <span>TOTAL RESERVATION</span>
            <strong id="confirmationTotal">Rp {{ number_format($booking->grand_total, 0, ',', '.') }}</strong>
        </div>

        <p class="confirmation-note">
            A confirmation of your reservation has been saved. Please keep your booking number for reference.
        </p>

        <div class="confirmation-actions">
            <a href="{{ route('home') }}" class="confirmation-button primary">BACK TO HOME</a>
            <button onclick="window.print()" class="confirmation-button secondary">PRINT DETAILS</button>
        </div>

    </section>

</main>

</x-hotel-layout>
