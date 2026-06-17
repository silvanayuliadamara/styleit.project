@extends('layouts.owner', ['title' => 'Edit Kategori Layanan — LYB'])

@section('owner_content')
    <div class="mb-3">
        <a href="{{ route('owner.categories.index') }}" class="btn btn-sm btn-outline-secondary" style="border-radius: 8px; border-color: #eadfd6; color: #211313;">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Kategori
        </a>
    </div>

    <header class="lyb-admin-page-header">
        <div>
            <h2>Edit Kategori Layanan</h2>
            <p>Perbarui rincian kategori <strong>{{ $category->name }}</strong>.</p>
        </div>
    </header>

    <section class="lyb-admin-section">
        <div class="card p-4 mx-auto" style="max-width: 600px; border-radius: 18px; border: 1px solid #eadfd6; background: #fff;">
            <form action="{{ route('owner.categories.update', $category) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label fw-bold small text-muted">Nama Kategori <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $category->name) }}" placeholder="Contoh: Wedding, Prewedding" required style="border-radius: 10px; border-color: #eadfd6;">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="headline" class="form-label fw-bold small text-muted">Headline / Tagline Singkat</label>
                    <input type="text" name="headline" id="headline" class="form-control @error('headline') is-invalid @enderror" value="{{ old('headline', $category->headline) }}" placeholder="Contoh: Hari Sakral Anda, Sempurna" style="border-radius: 10px; border-color: #eadfd6;">
                    @error('headline')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label fw-bold small text-muted">Deskripsi Lengkap</label>
                    <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="4" placeholder="Jelaskan mengenai apa saja layanan yang masuk ke dalam kategori ini..." style="border-radius: 10px; border-color: #eadfd6;">{{ old('description', $category->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-6">
                        <label for="icon" class="form-label fw-bold small text-muted">Ikon (Bootstrap Icon Class)</label>
                        <input type="text" name="icon" id="icon" class="form-control @error('icon') is-invalid @enderror" value="{{ old('icon', $category->icon) }}" placeholder="Contoh: bi-gem, bi-heart" style="border-radius: 10px; border-color: #eadfd6;">
                        <small class="text-muted" style="font-size: 11px;">Rujukan: <a href="https://icons.getbootstrap.com/" target="_blank">Bootstrap Icons</a></small>
                        @error('icon')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-6">
                        <label for="sort_order" class="form-label fw-bold small text-muted">Urutan Tampilan</label>
                        <input type="number" name="sort_order" id="sort_order" class="form-control @error('sort_order') is-invalid @enderror" value="{{ old('sort_order', $category->sort_order) }}" style="border-radius: 10px; border-color: #eadfd6;">
                        @error('sort_order')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="btn btn-dark w-100 fw-bold py-2" style="border-radius: 10px; background: #211313; border: none;">
                    Perbarui Kategori
                </button>
            </form>
        </div>
    </section>
@endsection
