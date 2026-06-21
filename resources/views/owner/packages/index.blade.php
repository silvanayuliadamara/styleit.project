@extends('layouts.owner', ['title' => 'Kelola Paket Layanan — LYB'])

@section('owner_content')
    {{-- Page Header --}}
    <header class="lyb-admin-page-header">
        <div>
            <h2>Paket Layanan</h2>
            <p>Kelola rincian paket layanan makeup, baju, dan pricing untuk customer.</p>
        </div>
        <div>
            <a href="{{ route('owner.packages.create') }}" class="btn btn-dark fw-bold px-3 py-2" style="border-radius: 10px; background: #211313; border: none;">
                <i class="bi bi-plus-lg"></i> Tambah Paket
            </a>
        </div>
    </header>

    {{-- Packages List --}}
    <section class="lyb-admin-section">
        <div class="lyb-admin-table-card">
            <div class="table-responsive">
                <table class="table lyb-admin-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Gambar</th>
                            <th>Nama Paket</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>DP Wajib</th>
                            <th>Kuota / Hari</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($packages as $package)
                            <tr>
                                <td><code>{{ $package->code }}</code></td>
                                <td>
                                    @if($package->image)
                                        <img src="{{ str_starts_with($package->image, 'images/') ? asset($package->image) : asset('storage/' . $package->image) }}" alt="{{ $package->name }}" class="rounded shadow-sm" style="width: 48px; height: 48px; object-fit: cover; border: 1px solid #eadfd6;">
                                    @else
                                        <div class="rounded bg-light text-muted d-flex align-items-center justify-content-center shadow-sm" style="width: 48px; height: 48px; border: 1px solid #eadfd6;">
                                            <i class="bi bi-image" style="font-size: 16px;"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $package->name }}</div>
                                    @if($package->is_popular)
                                        <small class="badge bg-warning text-dark" style="font-size: 9px;"><i class="bi bi-star-fill"></i> Populer</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border" style="border-color: #eadfd6 !important;">
                                        {{ $package->category->name ?? '-' }}
                                    </span>
                                </td>
                                <td><strong>Rp{{ number_format($package->price, 0, ',', '.') }}</strong></td>
                                <td>Rp{{ number_format($package->dp_amount, 0, ',', '.') }}</td>
                                <td><span class="badge bg-dark">{{ $package->quota_per_day }}</span></td>
                                <td>
                                    <span class="badge {{ $package->status == 'aktif' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ strtoupper($package->status) }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('owner.packages.edit', $package) }}" class="btn btn-sm btn-outline-dark" style="border-radius: 8px;">
                                            Edit
                                        </a>
                                        <form action="{{ route('owner.packages.destroy', $package) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus paket layanan ini?');">
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
                                <td colspan="9" class="text-center lyb-empty-row py-5">
                                    <i class="bi bi-box-seam fs-2"></i>
                                    <p class="mt-2 text-muted">Belum ada paket layanan. Silakan buat baru.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
