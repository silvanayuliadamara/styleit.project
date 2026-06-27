@extends('layouts.owner', ['title' => 'Kelola Addon & Tambahan — LYB'])

@section('owner_content')
    {{-- Page Header --}}
    <header class="lyb-admin-page-header">
        <div>
            <h2>Addon & Layanan Tambahan</h2>
            <p>Kelola layanan opsional (seperti makeup keluarga, sewa baju tambahan, henna, melati) beserta opsi harga.</p>
        </div>
        <div>
            <a href="{{ route('owner.addons.create') }}" class="btn btn-dark fw-bold px-3 py-2 lyb-btn-add" style="border-radius: 10px; background: #211313; border: none;">
                <i class="bi bi-plus-lg"></i> Tambah Addon
            </a>
        </div>
    </header>

    {{-- Addons List --}}
    <section class="lyb-admin-section">
        <div class="lyb-admin-table-card">
            <div class="table-responsive">
                <table class="table lyb-admin-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Nama Addon</th>
                            <th>Kategori Terkait</th>
                            <th>Harga Default</th>
                            <th>Opsi Addon (Harga & Vendor)</th>
                            <th>Dikerjakan Oleh</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($addons as $addon)
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark">{{ $addon->name }}</div>
                                    <small class="text-muted d-block" style="font-size: 11px;">{{ $addon->description ?? '-' }}</small>
                                </td>
                                <td>
                                    @forelse($addon->categories as $category)
                                        <span class="badge bg-light text-dark border me-1 mb-1" style="border-color: #eadfd6 !important;">
                                            {{ $category->name }}
                                        </span>
                                    @empty
                                        <span class="text-muted small">Semua Kategori</span>
                                    @endforelse
                                </td>
                                <td><strong>Rp{{ number_format($addon->price, 0, ',', '.') }}</strong></td>
                                <td>
                                    @if($addon->options->isNotEmpty())
                                        <ul class="list-unstyled mb-0" style="font-size: 11px;">
                                            @foreach($addon->options as $opt)
                                                <li class="mb-1">
                                                    <i class="bi bi-dot text-gold"></i>
                                                    {{ $opt->nama_option }}: <strong>Rp{{ number_format($opt->harga, 0, ',', '.') }}</strong>
                                                    @if($opt->is_pihak_lain)
                                                        <span class="badge bg-danger text-white ms-1" style="font-size: 8px;">Vendor</span>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span class="text-muted small">Tidak ada opsi khusus (hanya harga default)</span>
                                    @endif
                                </td>
                                <td>
                                    @if($addon->is_pihak_lain)
                                        <span class="badge bg-danger">Vendor Luar</span>
                                        <div class="text-muted" style="font-size: 9px;">Biaya: Rp{{ number_format($addon->biaya_pihak_lain, 0, ',', '.') }}</div>
                                    @else
                                        <span class="badge bg-success">MUA Lisa Yuli</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $addon->is_active ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $addon->is_active ? 'AKTIF' : 'NONAKTIF' }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('owner.addons.edit', $addon) }}" class="btn btn-sm btn-outline-dark" style="border-radius: 8px;">
                                            Edit
                                        </a>
                                        <form action="{{ route('owner.addons.destroy', $addon) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus addon ini?');">
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
                                    <i class="bi bi-plus-circle fs-2"></i>
                                    <p class="mt-2 text-muted">Belum ada addon layanan. Silakan buat baru.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
