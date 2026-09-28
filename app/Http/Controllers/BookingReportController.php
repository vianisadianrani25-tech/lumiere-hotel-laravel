<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class BookingReportController extends Controller
{
    public function index(Request $request): View
    {
        $bookings = $this->filteredBookings($request)
            ->latest('check_in')
            ->get();

        return view('laporan.pemesanan', [
            'bookings' => $bookings,
            'rooms' => Room::orderBy('name')->get(),
            'filters' => $this->filters($request),
            'summary' => $this->summary($bookings),
        ]);
    }

    public function exportPdf(Request $request): Response
    {
        $bookings = $this->filteredBookings($request)
            ->latest('check_in')
            ->get();

        $pdf = Pdf::loadView('laporan.pemesanan-pdf', [
            'bookings' => $bookings,
            'filters' => $this->filters($request),
            'summary' => $this->summary($bookings),
        ])->setPaper('a4', 'landscape');

        return $pdf->download('laporan-pemesanan-kamar-'.now()->format('Y-m-d').'.pdf');
    }

    /**
     * @return Builder<Booking>
     */
    private function filteredBookings(Request $request)
    {
        return Booking::query()
            ->with(['room', 'user'])
            ->when($request->filled('start_date'), fn ($query) => $query->whereDate('check_in', '>=', $request->date('start_date')))
            ->when($request->filled('end_date'), fn ($query) => $query->whereDate('check_in', '<=', $request->date('end_date')))
            ->when($request->filled('room_id'), fn ($query) => $query->where('room_id', $request->integer('room_id')))
            ->when($request->filled('status'), fn ($query) => $query->where('payment_status', $request->string('status')));
    }

    /**
     * @return array{start_date: ?string, end_date: ?string, room_id: ?int, status: ?string}
     */
    private function filters(Request $request): array
    {
        return [
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'room_id' => $request->input('room_id'),
            'status' => $request->input('status'),
        ];
    }

    /**
     * @param  Collection<int, Booking>  $bookings
     * @return array{total: int, paid: int, pending: int, cancelled: int, revenue: float}
     */
    private function summary($bookings): array
    {
        return [
            'total' => $bookings->count(),
            'paid' => $bookings->where('payment_status', 'paid')->count(),
            'pending' => $bookings->where('payment_status', 'pending')->count(),
            'cancelled' => $bookings->where('payment_status', 'cancelled')->count(),
            'revenue' => (float) $bookings->where('payment_status', 'paid')->sum('grand_total'),
        ];
    }
}
