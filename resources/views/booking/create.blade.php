@php($booking ??= null)

<x-hotel-layout :active="$booking ? 'my-bookings' : 'rooms'" body-class="booking-page" :title="$booking ? 'Edit Booking' : 'Booking'">

<section class="booking-header">
    <span>RESERVATION</span>
    @if ($booking)
        <h1>Edit Your Reservation</h1>
        <p>Update the details of reservation {{ $booking->booking_number }} below.</p>
    @else
        <h1>Complete Your Stay</h1>
        <p>Fill in your details below to reserve your room at Lumière Hotel.</p>
    @endif
</section>

<main class="booking-container">

    <section class="booking-form-card">

        <form method="POST" action="{{ $booking ? route('my-bookings.update', $booking) : route('booking.store') }}" id="bookingForm">
            @csrf
            @if ($booking)
                @method('PUT')
            @endif

            <div class="booking-step">
                <span class="step-number">01</span>
                <div>
                    <small>YOUR STAY</small>
                    <h2>Reservation Details</h2>
                </div>
            </div>

            <div class="booking-field">
                <label for="room_id">ROOM TYPE</label>
                <select id="room_id" name="room_id">
                    @foreach ($rooms as $option)
                        <option
                            value="{{ $option->id }}"
                            data-slug="{{ $option->slug }}"
                            data-price="{{ (int) $option->price }}"
                            data-capacity="{{ $option->capacity }}"
                            data-image="{{ $option->image_src }}"
                            @selected((int) old('room_id', $room->id) === $option->id)
                        >
                            {{ $option->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="booking-two-column">
                <div class="booking-field">
                    <label for="check_in">CHECK IN</label>
                    <input type="date" id="check_in" name="check_in" value="{{ old('check_in', $booking?->check_in->format('Y-m-d')) }}">
                </div>

                <div class="booking-field">
                    <label for="check_out">CHECK OUT</label>
                    <input type="date" id="check_out" name="check_out" value="{{ old('check_out', $booking?->check_out->format('Y-m-d')) }}">
                </div>
            </div>

            <div class="booking-field">
                <label for="guests">NUMBER OF GUESTS</label>
                <select id="guests" name="guests">
                    @for ($i = 1; $i <= 4; $i++)
                        <option value="{{ $i }}" @selected(old('guests', $booking?->guests ?? 2) == $i)>{{ $i }} Guest{{ $i > 1 ? 's' : '' }}</option>
                    @endfor
                </select>
            </div>

            <div class="booking-step second-step">
                <span class="step-number">02</span>
                <div>
                    <small>GUEST INFORMATION</small>
                    <h2>Your Details</h2>
                </div>
            </div>

            <div class="booking-field">
                <label for="guest_name">FULL NAME</label>
                <input type="text" id="guest_name" name="guest_name" value="{{ old('guest_name', $booking?->guest_name) }}" placeholder="Enter your full name">
            </div>

            <div class="booking-two-column">
                <div class="booking-field">
                    <label for="email">EMAIL</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $booking?->email ?? auth()->user()->email) }}" placeholder="you@email.com">
                </div>

                <div class="booking-field">
                    <label for="phone">PHONE NUMBER</label>
                    <input type="tel" id="phone" name="phone" value="{{ old('phone', $booking?->phone) }}" placeholder="+62">
                </div>
            </div>

            <div class="booking-field">
                <label for="special_request">SPECIAL REQUEST <span>(OPTIONAL)</span></label>
                <textarea id="special_request" name="special_request" rows="4" placeholder="Any special request for your stay?">{{ old('special_request', $booking?->special_request) }}</textarea>
            </div>

            @if ($errors->any())
                <div class="booking-error show">⚠ {{ $errors->first() }}</div>
            @endif

            <button type="submit" class="confirm-booking">{{ $booking ? 'SAVE CHANGES' : 'CONTINUE TO PAYMENT' }} <span>→</span></button>

        </form>

    </section>

    <aside class="booking-summary">

        <div class="summary-image">
            <img id="selectedRoomImage" src="{{ $room->image_src }}" alt="Selected Room">
        </div>

        <div class="summary-content">

            <span class="summary-label">YOUR ROOM</span>
            <h2 id="selectedRoom">{{ $room->name }}</h2>

            <div class="summary-rating">★ {{ number_format((float) $room->rating, 1) }} <span>Excellent</span></div>

            <div class="summary-line"></div>

            <div class="summary-row">
                <span>Check in</span>
                <strong id="summaryCheckIn">—</strong>
            </div>

            <div class="summary-row">
                <span>Check out</span>
                <strong id="summaryCheckOut">—</strong>
            </div>

            <div class="summary-row">
                <span>Guests</span>
                <strong id="summaryGuests">2 Guests</strong>
            </div>

            <div class="summary-line"></div>

            <div class="summary-price-label">ROOM PRICE</div>
            <div id="roomPrice" class="room-price">Rp {{ number_format((float) $room->price, 0, ',', '.') }} / night</div>

            <div class="summary-total">
                <span>TOTAL</span>
                <strong id="totalPrice">Rp 0</strong>
            </div>

            <p class="summary-note">Final price will be calculated based on the number of nights.</p>

        </div>

    </aside>

</main>

<x-slot:scripts>
<script>
const roomSelect = document.getElementById('room_id');
const checkIn = document.getElementById('check_in');
const checkOut = document.getElementById('check_out');
const guests = document.getElementById('guests');

const today = new Date().toISOString().split('T')[0];
if (checkIn) checkIn.min = today;
if (checkOut) checkOut.min = today;

function formatRupiah(value) {
    return 'Rp ' + Math.max(0, value).toLocaleString('id-ID');
}

function updateRoomPreview() {
    const option = roomSelect.options[roomSelect.selectedIndex];
    document.getElementById('selectedRoom').textContent = option.text.trim();
    document.getElementById('selectedRoomImage').src = option.dataset.image;
    document.getElementById('roomPrice').textContent = formatRupiah(Number(option.dataset.price)) + ' / night';
    calculateTotal();
}

function calculateTotal() {
    const option = roomSelect.options[roomSelect.selectedIndex];
    const price = Number(option.dataset.price);

    if (!checkIn.value || !checkOut.value) {
        document.getElementById('totalPrice').textContent = 'Rp 0';
        return;
    }

    const nights = Math.ceil((new Date(checkOut.value) - new Date(checkIn.value)) / 86400000);

    document.getElementById('summaryCheckIn').textContent = checkIn.value;
    document.getElementById('summaryCheckOut').textContent = checkOut.value;

    if (nights <= 0) {
        document.getElementById('totalPrice').textContent = 'Rp 0';
        return;
    }

    document.getElementById('totalPrice').textContent = formatRupiah(nights * price);
}

if (roomSelect) roomSelect.addEventListener('change', updateRoomPreview);
if (checkIn) checkIn.addEventListener('change', calculateTotal);
if (checkOut) checkOut.addEventListener('change', calculateTotal);
function updateGuests() {
    document.getElementById('summaryGuests').textContent = guests.value + (guests.value == 1 ? ' Guest' : ' Guests');
}

if (guests) guests.addEventListener('change', updateGuests);

if (roomSelect) updateRoomPreview();
if (guests) updateGuests();
</script>
</x-slot:scripts>

</x-hotel-layout>
