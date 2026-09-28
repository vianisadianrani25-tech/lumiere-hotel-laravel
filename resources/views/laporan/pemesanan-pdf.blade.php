<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pemesanan Kamar</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: Helvetica, Arial, sans-serif;
            color: #29231f;
            font-size: 11px;
        }

        .pdf-header {
            text-align: center;
            border-bottom: 2px solid #b99754;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }

        .pdf-header h1 {
            font-size: 20px;
            color: #29231f;
            margin-bottom: 4px;
        }

        .pdf-header span {
            font-size: 10px;
            color: #877e74;
            letter-spacing: 1px;
        }

        .pdf-meta {
            width: 100%;
            margin-bottom: 14px;
            font-size: 10px;
            color: #56504a;
        }

        .pdf-summary {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }

        .pdf-summary td {
            border: 1px solid #e2d9cd;
            padding: 8px 10px;
            text-align: center;
        }

        .pdf-summary span {
            display: block;
            font-size: 8px;
            color: #877e74;
            letter-spacing: 1px;
            margin-bottom: 4px;
        }

        .pdf-summary strong {
            font-size: 13px;
        }

        table.pdf-table {
            width: 100%;
            border-collapse: collapse;
        }

        table.pdf-table th {
            background: #29231f;
            color: #fff;
            font-size: 9px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            padding: 7px 6px;
            text-align: left;
        }

        table.pdf-table td {
            border-bottom: 1px solid #e2d9cd;
            padding: 6px;
            font-size: 10px;
        }

        table.pdf-table tr:nth-child(even) td {
            background: #f8f5ef;
        }

        .pdf-footer {
            margin-top: 20px;
            font-size: 9px;
            color: #877e74;
            text-align: right;
        }
    </style>
</head>
<body>

    <div class="pdf-header">
        <h1>LUMIÈRE HOTEL &amp; RESORT</h1>
        <span>LAPORAN PEMESANAN KAMAR</span>
    </div>

    <table class="pdf-meta">
        <tr>
            <td>Periode Check In</td>
            <td>:</td>
            <td>{{ $filters['start_date'] ? \Illuminate\Support\Carbon::parse($filters['start_date'])->format('d-m-Y') : '—' }} s/d {{ $filters['end_date'] ? \Illuminate\Support\Carbon::parse($filters['end_date'])->format('d-m-Y') : '—' }}</td>
            <td style="text-align: right;">Status</td>
            <td>:</td>
            <td>{{ $filters['status'] ? ucfirst($filters['status']) : 'Semua Status' }}</td>
        </tr>
        <tr>
            <td>Dicetak</td>
            <td>:</td>
            <td colspan="4">{{ now()->format('d-m-Y H:i') }} WIB</td>
        </tr>
    </table>

    <table class="pdf-summary">
        <tr>
            <td><span>TOTAL BOOKING</span><strong>{{ $summary['total'] }}</strong></td>
            <td><span>PAID</span><strong>{{ $summary['paid'] }}</strong></td>
            <td><span>PENDING</span><strong>{{ $summary['pending'] }}</strong></td>
            <td><span>CANCELLED</span><strong>{{ $summary['cancelled'] }}</strong></td>
            <td><span>TOTAL PENDAPATAN (PAID)</span><strong>Rp {{ number_format($summary['revenue'], 0, ',', '.') }}</strong></td>
        </tr>
    </table>

    <table class="pdf-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Booking No.</th>
                <th>Tamu</th>
                <th>Kamar</th>
                <th>Check In</th>
                <th>Check Out</th>
                <th>Malam</th>
                <th>Status</th>
                <th style="text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($bookings as $i => $booking)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $booking->booking_number }}</td>
                    <td>{{ $booking->guest_name }}</td>
                    <td>{{ $booking->room->name }}</td>
                    <td>{{ $booking->check_in->format('d-m-Y') }}</td>
                    <td>{{ $booking->check_out->format('d-m-Y') }}</td>
                    <td>{{ $booking->nights }}</td>
                    <td>{{ ucfirst($booking->payment_status) }}</td>
                    <td style="text-align: right;">Rp {{ number_format($booking->grand_total, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align: center; padding: 14px;">Tidak ada data pemesanan untuk filter ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="pdf-footer">
        Lumière Hotel &amp; Resort — Laporan Pemesanan Kamar
    </div>

</body>
</html>
