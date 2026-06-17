@extends('layouts.owner', ['title' => 'Kelola Kategori Layanan — LYB'])

@section('owner_content')
    {{-- Page Header --}}
    <header class="lyb-admin-page-header">
        <div>
            <h2>Kategori Layanan</h2>
            <p>Kelola kategori utama layanan (seperti Wedding, Prewedding, Regular, Baju).</p>
        </div>
        <div>
            <a href="{{ route('owner.categories.create') }}" class="btn btn-dark fw-bold px-3 py-2" style="border-radius: 10px; background: #211313; border: none;">
                <i class="bi bi-plus-lg"></i> Tambah Kategori
            </a>
        </div>
    </header>

    {{-- Categories List --}}
    <section class="lyb-admin-section">
        <div class="lyb-admin-table-card">
            <div class="table-responsive">
                <table class="table lyb-admin-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Urutan</th>
                            <th>Ikon</th>
                            <th>Nama Kategori</th>
                            <th>Slug</th>
                            <th>Headline</th>
                            <th>Keterangan</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categories as $category)
                            <tr>
                                <td><span class="badge bg-secondary">{{ $category->sort_order }}</span></td>
                                <td>
                                    <div class="fs-4 text-gold">
                                        <i class="bi {{ $category->icon ?? 'bi-tag-fill' }}"></i>
                                    </div>
                                </td>
                                <td><strong>{{ $category->name }}</strong></td>
                                <td><code>{{ $category->slug }}</code></td>
                                <td>{{ $category->headline ?? '-' }}</td>
                                <td style="max-width: 280px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                    {{ $category->description ?? '-' }}
                                </td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('owner.categories.edit', $category) }}" class="btn btn-sm btn-outline-dark" style="border-radius: 8px;">
                                            Edit
                                        </a>
                                        <form action="{{ route('owner.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius: 8px;">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center lyb-empty-row py-5">
                                    <i class="bi bi-tags fs-2"></i>
                                    <p class="mt-2 text-muted">Belum ada kategori layanan. Silakan buat baru.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
