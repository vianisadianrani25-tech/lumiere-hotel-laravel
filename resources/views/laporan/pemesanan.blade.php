<x-hotel-layout active="laporan-pemesanan" body-class="my-booking-page" title="Laporan Pemesanan Kamar">

<section class="booking-page-header">
    <span>REPORT</span>
    <h1>Laporan Pemesanan Kamar</h1>
    <p>Rekap seluruh data pemesanan kamar Lumière Hotel & Resort.</p>
</section>

<main class="report-container">

    <form method="GET" action="{{ route('laporan-pemesanan.index') }}" class="report-filter">

        <div class="report-filter-field">
            <label for="start_date">Check In Dari</label>
            <input type="date" id="start_date" name="start_date" value="{{ $filters['start_date'] }}">
        </div>

        <div class="report-filter-field">
            <label for="end_date">Check In Sampai</label>
            <input type="date" id="end_date" name="end_date" value="{{ $filters['end_date'] }}">
        </div>

        <div class="report-filter-field">
            <label for="room_id">Kamar</label>
            <select id="room_id" name="room_id">
                <option value="">Semua Kamar</option>
                @foreach ($rooms as $room)
                    <option value="{{ $room->id }}" @selected((string) $filters['room_id'] === (string) $room->id)>{{ $room->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="report-filter-field">
            <label for="status">Status</label>
            <select id="status" name="status">
                <option value="">Semua Status</option>
                <option value="paid" @selected($filters['status'] === 'paid')>Paid</option>
                <option value="pending" @selected($filters['status'] === 'pending')>Pending</option>
                <option value="cancelled" @selected($filters['status'] === 'cancelled')>Cancelled</option>
            </select>
        </div>

        <div class="report-filter-actions">
            <button type="submit" class="report-filter-button">TERAPKAN FILTER</button>
            <a href="{{ route('laporan-pemesanan.index') }}" class="report-reset-link">Reset</a>
        </div>

    </form>

    <div class="report-summary">
        <div class="report-summary-card">
            <span>TOTAL BOOKING</span>
            <strong>{{ $summary['total'] }}</strong>
        </div>
        <div class="report-summary-card">
            <span>PAID</span>
            <strong>{{ $summary['paid'] }}</strong>
        </div>
        <div class="report-summary-card">
            <span>PENDING</span>
            <strong>{{ $summary['pending'] }}</strong>
        </div>
        <div class="report-summary-card">
            <span>CANCELLED</span>
            <strong>{{ $summary['cancelled'] }}</strong>
        </div>
        <div class="report-summary-card highlight">
            <span>TOTAL PENDAPATAN (PAID)</span>
            <strong>Rp {{ number_format($summary['revenue'], 0, ',', '.') }}</strong>
        </div>
    </div>

    <div class="report-actions">
        <a href="{{ route('laporan-pemesanan.export-pdf', $filters) }}" class="report-export-button">⬇ EXPORT PDF</a>
    </div>

    <div class="report-table-wrapper">
        <table class="report-table">
            <thead>
                <tr>
                    <th>Booking No.</th>
                    <th>Tamu</th>
                    <th>Kamar</th>
                    <th>Check In</th>
                    <th>Check Out</th>
                    <th>Malam</th>
                    <th>Status</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($bookings as $booking)
                    <tr>
                        <td>{{ $booking->booking_number }}</td>
                        <td>{{ $booking->guest_name }}</td>
                        <td>{{ $booking->room->name }}</td>
                        <td>{{ $booking->check_in->format('d-m-Y') }}</td>
                        <td>{{ $booking->check_out->format('d-m-Y') }}</td>
                        <td>{{ $booking->nights }}</td>
                        <td>
                            <span class="report-status report-status-{{ $booking->payment_status }}">
                                {{ ucfirst($booking->payment_status) }}
                            </span>
                        </td>
                        <td>Rp {{ number_format($booking->grand_total, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="report-empty">Tidak ada data pemesanan untuk filter ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</main>

</x-hotel-layout>
