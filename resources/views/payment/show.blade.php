<x-hotel-layout body-class="payment-page" title="Payment">

<section class="payment-header">
    <span>PEMBAYARAN AMAN</span>
    <h1>Selesaikan Pembayaran Anda</h1>
    <p>Pilih metode pembayaran yang Anda inginkan untuk menyelesaikan reservasi Anda.</p>
</section>

<main class="payment-container">

    <section class="payment-card">

        <form method="POST" action="{{ route('payment.process', $booking) }}" id="paymentForm">
            @csrf

            <div class="payment-step">
                <span>03</span>
                <div>
                    <small>Metode Pembayaran</small>
                    <h2>Bagaimana Anda ingin melakukan pembayaran?</h2>
                </div>
            </div>

            <div class="payment-methods">

                <label class="payment-method">
                    <input type="radio" name="payment_method" value="bank" checked>
                    <div class="payment-method-content">
                        <div class="payment-method-icon">🏦</div>
                        <div><strong>Bank Transfer</strong><small>BCA, BNI, BRI, Mandiri</small></div>
                    </div>
                </label>

                <label class="payment-method">
                    <input type="radio" name="payment_method" value="ewallet">
                    <div class="payment-method-content">
                        <div class="payment-method-icon">📱</div>
                        <div><strong>E-Wallet</strong><small>QRIS, GoPay, OVO, DANA</small></div>
                    </div>
                </label>

                <label class="payment-method">
                    <input type="radio" name="payment_method" value="card">
                    <div class="payment-method-content">
                        <div class="payment-method-icon">💳</div>
                        <div><strong>Credit / Debit Card</strong><small>Visa, Mastercard</small></div>
                    </div>
                </label>

                <label class="payment-method">
                    <input type="radio" name="payment_method" value="hotel">
                    <div class="payment-method-content">
                        <div class="payment-method-icon">🏨</div>
                        <div><strong>Pay At Hotel</strong><small>Pay when you arrive</small></div>
                    </div>
                </label>

            </div>

            <div id="bankDetails" class="payment-extra">
                <div class="payment-info-title">BANK TRANSFER</div>
                <p>Transfer ke rekening berikut:</p>
                <div class="bank-box">
                    <strong>LUMIÈRE HOTEL</strong>
                    <span>Bank BCA</span>
                    <b>1234567890</b>
                    <span>a.n. Lumière Hotel & Resort</span>
                </div>
            </div>

            <div id="ewalletDetails" class="payment-extra" style="display:none;">
                <div class="payment-info-title">E-WALLET</div>
                <p>Pilih e-wallet yang ingin digunakan.</p>
                <select id="ewallet_type" name="ewallet_type">
                    <option value="QRIS">QRIS</option>
                    <option value="GoPay">GoPay</option>
                    <option value="OVO">OVO</option>
                    <option value="DANA">DANA</option>
                </select>
            </div>

            <div id="cardDetails" class="payment-extra" style="display:none;">
                <div class="payment-info-title">CARD DETAILS</div>

                <div class="payment-field">
                    <label>CARD HOLDER</label>
                    <input type="text" id="card_holder" name="card_holder" placeholder="Name on card">
                </div>

                <div class="payment-field">
                    <label>CARD NUMBER</label>
                    <input type="text" id="card_number" name="card_number" placeholder="0000000000000000" maxlength="16">
                </div>

                <div class="payment-two-column">
                    <div class="payment-field">
                        <label>EXPIRY</label>
                        <input type="text" id="card_expiry" name="card_expiry" placeholder="MM/YY">
                    </div>

                    <div class="payment-field">
                        <label>CVV</label>
                        <input type="password" id="card_cvv" name="card_cvv" placeholder="•••" maxlength="4">
                    </div>
                </div>
            </div>

            @if ($errors->any())
                <div class="payment-error show">⚠ {{ $errors->first() }}</div>
            @endif

            <button type="submit" id="payNow" class="pay-now">BAYAR SEKARANG <span>→</span></button>

            <p class="payment-security">🔒 This is a simulated payment for academic project purposes.</p>

        </form>

    </section>

    <aside class="payment-summary">

        <div class="payment-summary-header">
            <span>RESERVATION SUMMARY</span>
            <h2>{{ $booking->room->name }}</h2>
        </div>

        <div class="payment-summary-body">

            <div class="payment-summary-row"><span>Guest</span><strong>{{ $booking->guest_name }}</strong></div>
            <div class="payment-summary-row"><span>Check in</span><strong>{{ $booking->check_in->format('Y-m-d') }}</strong></div>
            <div class="payment-summary-row"><span>Check out</span><strong>{{ $booking->check_out->format('Y-m-d') }}</strong></div>
            <div class="payment-summary-row"><span>Nights</span><strong>{{ $booking->nights }}</strong></div>
            <div class="payment-summary-row"><span>Guests</span><strong>{{ $booking->guests }}</strong></div>

            <div class="payment-divider"></div>

            <div class="payment-summary-price"><span>ROOM</span><strong>Rp {{ number_format((float) $booking->total, 0, ',', '.') }}</strong></div>
            <div class="payment-summary-price"><span>SERVICE FEE</span><strong>Rp {{ number_format(\App\Models\Booking::SERVICE_FEE, 0, ',', '.') }}</strong></div>

            <div class="payment-total"><span>TOTAL</span><strong>Rp {{ number_format($booking->grand_total, 0, ',', '.') }}</strong></div>

        </div>

    </aside>

</main>

<x-slot:scripts>
<script>
const methodRadios = document.querySelectorAll('input[name="payment_method"]');
const bankDetails = document.getElementById('bankDetails');
const ewalletDetails = document.getElementById('ewalletDetails');
const cardDetails = document.getElementById('cardDetails');

function toggleMethod() {
    const selected = document.querySelector('input[name="payment_method"]:checked').value;
    bankDetails.style.display = selected === 'bank' ? 'block' : 'none';
    ewalletDetails.style.display = selected === 'ewallet' ? 'block' : 'none';
    cardDetails.style.display = selected === 'card' ? 'block' : 'none';
}

methodRadios.forEach(radio => radio.addEventListener('change', toggleMethod));
toggleMethod();
</script>
</x-slot:scripts>

</x-hotel-layout>
