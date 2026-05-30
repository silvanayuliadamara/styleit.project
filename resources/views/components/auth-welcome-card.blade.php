<div class="welcome-card">
    <div>
        <div class="welcome-brand">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="welcome-logo">

            <div>
                <div class="welcome-brand-title">LISA YULI BELTI</div>
                <div class="welcome-brand-subtitle">WEDDING GALLERY DAN MAKEUP ARTIST</div>
            </div>
        </div>

        <div class="welcome-content">
            <p class="welcome-label">
                {{ $label ?? 'WEDDING GALLERY & MAKEUP ARTIST' }}
            </p>

            <h1>{{ $title ?? 'Selamat datang kembali, Cantik.' }}</h1>

            <p class="welcome-description">
                {{ $subtitle ?? 'Masuk untuk melanjutkan booking, melihat invoice, atau mengelola akun Anda. Setiap detail kami rancang untuk pengalaman premium Anda.' }}
            </p>
        </div>
    </div>

    <div class="quote">
        <em>"{{ $quote ?? 'Setiap pengantin berhak merasa istimewa di hari spesialnya.' }}"</em>

        <div class="quote-author">
            — {{ $author ?? 'LISA YULI BELTI' }}
        </div>
    </div>
</div>
