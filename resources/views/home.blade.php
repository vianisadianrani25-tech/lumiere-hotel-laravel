<x-hotel-layout active="home" body-class="hotel-home" title="Home">

<section class="hotel-hero">

    <div class="hero-dark"></div>

    <div class="hero-content">
        <span class="hero-small">WELCOME TO LUMIÈRE</span>
        <h1>Your Perfect<br>Stay Awaits.</h1>
        <p>Experience exceptional comfort, elegant rooms, and unforgettable moments in the heart of the city.</p>
        <a href="#rooms" class="hero-button">EXPLORE OUR ROOMS <span>→</span></a>
    </div>

</section>

<section class="hotel-search">

    <div class="search-item">
        <span class="search-icon">📍</span>
        <div><small>LOCATION</small><strong>Medan, Indonesia</strong></div>
    </div>

    <div class="search-item">
        <span class="search-icon">📅</span>
        <div><small>CHECK IN</small><strong>Pilih tanggal</strong></div>
    </div>

    <div class="search-item">
        <span class="search-icon">📅</span>
        <div><small>CHECK OUT</small><strong>Pilih tanggal</strong></div>
    </div>

    <div class="search-item">
        <span class="search-icon">👤</span>
        <div><small>GUESTS</small><strong>2 Guests</strong></div>
    </div>

    <a href="#rooms" class="search-button">SEARCH</a>

</section>

<main class="hotel-content">

<section class="hotel-section" id="rooms">

    <div class="section-heading">
        <div>
            <span>OUR ACCOMMODATION</span>
            <h2>Find Your Perfect Room</h2>
        </div>
        <p>Choose from our carefully selected rooms designed for your comfort and unforgettable stay.</p>
    </div>

    <div class="room-grid">

        @foreach ($rooms as $room)
            <article class="room-card">

                <div class="room-image">
                    <img src="{{ $room->image_src }}" alt="{{ $room->name }}">
                    @if ($room->badge)
                        <span class="room-badge @if($room->badge === 'LUXURY') luxury @endif">{{ $room->badge }}</span>
                    @endif
                </div>

                <div class="room-info">

                    <div class="room-top">
                        <div>
                            <small>{{ $room->category }}</small>
                            <h3>{{ $room->name }}</h3>
                        </div>
                        <span>★ {{ number_format((float) $room->rating, 1) }}</span>
                    </div>

                    <p>{{ $room->description }}</p>

                    <div class="room-bottom">
                        <div>
                            <strong>Rp {{ number_format((float) $room->price, 0, ',', '.') }}</strong>
                            <small>/ night</small>
                        </div>

                        <a href="{{ route('booking.create', $room) }}">BOOK NOW →</a>
                    </div>

                </div>

            </article>
        @endforeach

    </div>

</section>

<section class="hotel-section facilities-section" id="facilities">

    <div class="section-heading">
        <div>
            <span>EXPERIENCE</span>
            <h2>Everything You Need</h2>
        </div>
    </div>

    <div class="facility-grid">

        <div class="facility-card" onclick="openFacility('Swimming Pool', 'Bersantai dan nikmati kolam renang kami yang indah dengan suasana yang nyaman dan tenang.', 'https://images.unsplash.com/photo-1602002418082-a4443e081dd1?auto=format&fit=crop&w=1000&q=85')">
            <div class="facility-icon">🏊</div>
            <h3>Swimming Pool</h3>
            <p>Relax and enjoy our beautiful swimming pool.</p>
            <span class="facility-arrow">→</span>
        </div>

        <div class="facility-card" onclick="openFacility('Restaurant', 'Nikmati berbagai hidangan lezat yang disiapkan oleh chef terbaik kami dalam suasana restoran yang nyaman.', 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=1000&q=85')">
            <div class="facility-icon">☕</div>
            <h3>Restaurant</h3>
            <p>Enjoy delicious dishes prepared by our chefs.</p>
            <span class="facility-arrow">→</span>
        </div>

        <div class="facility-card" onclick="openFacility('Spa & Wellness', 'Luangkan waktu untuk bersantai dan menyegarkan tubuh serta pikiran Anda dengan berbagai layanan wellness.', 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?auto=format&fit=crop&w=1000&q=85')">
            <div class="facility-icon">💆</div>
            <h3>Spa & Wellness</h3>
            <p>Take a moment to relax and refresh your body.</p>
            <span class="facility-arrow">→</span>
        </div>

        <div class="facility-card" onclick="openFacility('Free Parking', 'Area parkir yang nyaman dan aman tersedia bagi seluruh tamu Lumière Hotel.', 'https://images.unsplash.com/photo-1506521781263-d8422e82f27a?auto=format&fit=crop&w=1000&q=85')">
            <div class="facility-icon">🚗</div>
            <h3>Free Parking</h3>
            <p>Convenient parking area available for our guests.</p>
            <span class="facility-arrow">→</span>
        </div>

    </div>

</section>

<section class="about-hotel" id="about">

    <div class="about-image">
        <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=1200&q=85" alt="Lumière Hotel">
    </div>

    <div class="about-text">
        <span>ABOUT LUMIÈRE</span>
        <h2>A Place To Stay, A Memory To Keep.</h2>
        <p>Lumière Hotel & Resort is designed for guests who appreciate comfort, elegance, and meaningful moments.</p>
        <p>From the moment you arrive, every detail is prepared to make your stay memorable.</p>
        <a href="#rooms">DISCOVER YOUR ROOM →</a>
    </div>

</section>

</main>

<div id="facilityModal" class="facility-modal">

    <div class="facility-modal-content">

        <button class="modal-close" onclick="closeFacility()">×</button>

        <div class="modal-image">
            <img id="modalImage" src="" alt="Fasilitas Lumière Hotel">
        </div>

        <div class="modal-info">
            <span>EXPERIENCE</span>
            <h2 id="modalTitle">Swimming Pool</h2>
            <p id="modalDescription">Deskripsi fasilitas hotel.</p>

            <div class="facility-benefits">
                <div>✓ Fasilitas nyaman</div>
                <div>✓ Lingkungan bersih</div>
                <div>✓ Cocok untuk tamu hotel</div>
            </div>

            <button class="close-button" onclick="closeFacility()">TUTUP</button>
        </div>

    </div>

</div>

<x-slot:scripts>
<script>
function openFacility(title, description, image) {
    document.getElementById('modalTitle').textContent = title;
    document.getElementById('modalDescription').textContent = description;
    document.getElementById('modalImage').src = image;
    document.getElementById('facilityModal').classList.add('show');
}

function closeFacility() {
    document.getElementById('facilityModal').classList.remove('show');
}

const facilityModal = document.getElementById('facilityModal');
if (facilityModal) {
    facilityModal.addEventListener('click', function (event) {
        if (event.target === this) {
            closeFacility();
        }
    });
}

document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape') {
        closeFacility();
    }
});
</script>
</x-slot:scripts>

</x-hotel-layout>
