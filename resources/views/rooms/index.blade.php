<x-hotel-layout active="kelola-kamar" body-class="my-booking-page" title="Kelola Kamar">

<section class="booking-page-header">
    <span>ROOMS</span>
    <h1>Kelola Kamar</h1>
    <p>Tambah, ubah, dan hapus data kamar Lumière Hotel & Resort.</p>
</section>

<main class="report-container">

    @if (session('status'))
        <div class="room-flash">{{ session('status') }}</div>
    @endif

    @if (session('error'))
        <div class="booking-error show">⚠ {{ session('error') }}</div>
    @endif

    <div class="report-actions">
        <a href="{{ route('rooms.create') }}" class="report-export-button">+ TAMBAH KAMAR</a>
    </div>

    <div class="report-table-wrapper">
        <table class="report-table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Kategori</th>
                    <th>Badge</th>
                    <th>Harga / Malam</th>
                    <th>Kapasitas</th>
                    <th>Rating</th>
                    <th>Booking</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($rooms as $room)
                    <tr>
                        <td>{{ $room->name }}</td>
                        <td>{{ $room->category }}</td>
                        <td>{{ $room->badge ?? '—' }}</td>
                        <td>Rp {{ number_format((float) $room->price, 0, ',', '.') }}</td>
                        <td>{{ $room->capacity }} orang</td>
                        <td>★ {{ number_format((float) $room->rating, 1) }}</td>
                        <td>{{ $room->bookings_count }}</td>
                        <td>
                            <div class="room-table-actions">
                                <a href="{{ route('rooms.edit', $room) }}" class="room-edit-link">Edit</a>

                                <form method="POST" action="{{ route('rooms.destroy', $room) }}" onsubmit="return confirm('Hapus kamar {{ $room->name }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="room-delete-button">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="report-empty">Belum ada data kamar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</main>

</x-hotel-layout>
