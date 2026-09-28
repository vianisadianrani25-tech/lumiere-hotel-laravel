<x-hotel-layout active="kelola-kamar" body-class="booking-page" :title="$room->exists ? 'Edit Kamar' : 'Tambah Kamar'">

<section class="booking-header">
    <span>ROOMS</span>
    <h1>{{ $room->exists ? 'Edit Kamar' : 'Tambah Kamar' }}</h1>
    <p>{{ $room->exists ? "Perbarui data kamar {$room->name}." : 'Isi data kamar baru di bawah ini.' }}</p>
</section>

<main class="room-form-container">

    <section class="booking-form-card">

        <form method="POST" action="{{ $room->exists ? route('rooms.update', $room) : route('rooms.store') }}" enctype="multipart/form-data">
            @csrf
            @if ($room->exists)
                @method('PUT')
            @endif

            <div class="booking-field">
                <label for="name">NAMA KAMAR</label>
                <input type="text" id="name" name="name" value="{{ old('name', $room->name) }}" placeholder="Deluxe Room">
            </div>

            <div class="booking-two-column">
                <div class="booking-field">
                    <label for="category">KATEGORI</label>
                    <input type="text" id="category" name="category" value="{{ old('category', $room->category) }}" placeholder="DELUXE">
                </div>

                <div class="booking-field">
                    <label for="badge">BADGE <span>(OPTIONAL)</span></label>
                    <input type="text" id="badge" name="badge" value="{{ old('badge', $room->badge) }}" placeholder="POPULAR">
                </div>
            </div>

            <div class="booking-two-column">
                <div class="booking-field">
                    <label for="price">HARGA / MALAM (RP)</label>
                    <input type="number" id="price" name="price" min="0" step="1000" value="{{ old('price', $room->price !== null ? (int) $room->price : null) }}">
                </div>

                <div class="booking-field">
                    <label for="capacity">KAPASITAS (ORANG)</label>
                    <input type="number" id="capacity" name="capacity" min="1" max="10" value="{{ old('capacity', $room->capacity) }}">
                </div>
            </div>

            <div class="booking-two-column">
                <div class="booking-field">
                    <label for="rating">RATING (0 - 5)</label>
                    <input type="number" id="rating" name="rating" min="0" max="5" step="0.1" value="{{ old('rating', $room->rating ?? 5.0) }}">
                </div>

                <div class="booking-field">
                    <label for="image">GAMBAR KAMAR @if ($room->exists)<span>(OPSIONAL)</span>@endif</label>
                    <input type="file" id="image" name="image" accept=".jpeg,.jpg,.png,image/jpeg,image/png">
                    <small class="room-image-hint">Format jpeg, png, jpg. Maksimal 2 MB.</small>
                </div>
            </div>

            <div class="room-image-preview" id="roomImagePreviewWrapper" @if (! $room->image_url) hidden @endif>
                <img id="roomImagePreview" src="{{ $room->image_url ? $room->image_src : '' }}" alt="Preview gambar kamar">
            </div>

            <div class="booking-field">
                <label for="description">DESKRIPSI</label>
                <textarea id="description" name="description" rows="4">{{ old('description', $room->description) }}</textarea>
            </div>

            @if ($errors->any())
                <div class="booking-error show">⚠ {{ $errors->first() }}</div>
            @endif

            <div class="room-form-actions">
                <a href="{{ route('rooms.index') }}" class="report-reset-link">Batal</a>
                <button type="submit" class="report-filter-button">{{ $room->exists ? 'SIMPAN PERUBAHAN' : 'TAMBAH KAMAR' }}</button>
            </div>

        </form>

    </section>

</main>

<script>
document.getElementById('image').addEventListener('change', function () {
    const file = this.files[0];

    if (! file) {
        return;
    }

    if (file.size > 2 * 1024 * 1024) {
        alert('Ukuran gambar maksimal 2 MB. Silakan pilih file yang lebih kecil.');
        this.value = '';
        return;
    }

    document.getElementById('roomImagePreview').src = URL.createObjectURL(file);
    document.getElementById('roomImagePreviewWrapper').hidden = false;
});
</script>

</x-hotel-layout>
