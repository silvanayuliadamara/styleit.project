@extends('layouts.owner', ['title' => 'Edit Paket Layanan — LYB'])

@section('owner_content')
    <div class="mb-3">
        <a href="{{ route('owner.packages.index') }}" class="btn btn-sm btn-outline-secondary" style="border-radius: 8px; border-color: #eadfd6; color: #211313;">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Paket
        </a>
    </div>

    <header class="lyb-admin-page-header">
        <div>
            <h2>Edit Paket Layanan: {{ $package->name }}</h2>
            <p>Perbarui konfigurasi harga, kuota, rule, dan item paket.</p>
        </div>
    </header>

    <section class="lyb-admin-section">
        <form action="{{ route('owner.packages.update', $package) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-4">
                <!-- Left: Package Fields -->
                <div class="col-12 col-lg-7">
                    <div class="card p-4 mb-4" style="border-radius: 18px; border: 1px solid #eadfd6; background: #fff;">
                        <h5 class="fw-bold mb-3" style="color: #211313;"><i class="bi bi-info-circle-fill text-gold"></i> Rincian Paket</h5>

                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label for="category_id" class="form-label fw-bold small text-muted">Kategori Layanan <span class="text-danger">*</span></label>
                                <select name="category_id" id="category_id" class="form-select" required style="border-radius: 10px; border-color: #eadfd6;">
                                    <option value="">Pilih Kategori</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $package->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="code" class="form-label fw-bold small text-muted">Kode Paket (Unique) <span class="text-danger">*</span></label>
                                <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code', $package->code) }}" placeholder="Contoh: PKG-WED-GOLD" required style="border-radius: 10px; border-color: #eadfd6;">
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="name" class="form-label fw-bold small text-muted">Nama Paket <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $package->name) }}" placeholder="Contoh: Paket Pernikahan Gold" required style="border-radius: 10px; border-color: #eadfd6;">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-4">
                                <label for="price" class="form-label fw-bold small text-muted">Harga Total (Rp) <span class="text-danger">*</span></label>
                                <input type="number" name="price" id="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $package->price) }}" placeholder="5000000" required style="border-radius: 10px; border-color: #eadfd6;">
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="dp_amount" class="form-label fw-bold small text-muted">DP Wajib (Rp) <span class="text-danger">*</span></label>
                                <input type="number" name="dp_amount" id="dp_amount" class="form-control @error('dp_amount') is-invalid @enderror" value="{{ old('dp_amount', $package->dp_amount) }}" placeholder="1000000" required style="border-radius: 10px; border-color: #eadfd6;">
                                @error('dp_amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="quota_per_day" class="form-label fw-bold small text-muted">Kuota Per Hari / Slot <span class="text-danger">*</span></label>
                                <input type="number" name="quota_per_day" id="quota_per_day" class="form-control @error('quota_per_day') is-invalid @enderror" value="{{ old('quota_per_day', $package->quota_per_day) }}" required style="border-radius: 10px; border-color: #eadfd6;">
                                @error('quota_per_day')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="description" class="form-label fw-bold small text-muted">Deskripsi Paket</label>
                                <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="3" placeholder="Tulis deskripsi ringkas tentang paket ini..." style="border-radius: 10px; border-color: #eadfd6;">{{ old('description', $package->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="image" class="form-label fw-bold small text-muted">Foto Cover Paket</label>
                                <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror" style="border-radius: 10px; border-color: #eadfd6;">
                                @if($package->image)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $package->image) }}" alt="{{ $package->name }}" class="img-thumbnail" style="max-height: 80px;">
                                        <small class="text-muted d-block">Biarkan kosong jika tidak ingin mengubah gambar cover.</small>
                                    </div>
                                @endif
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="status" class="form-label fw-bold small text-muted">Status <span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-select" required style="border-radius: 10px; border-color: #eadfd6;">
                                    <option value="aktif" {{ $package->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="nonaktif" {{ $package->status == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                </select>
                            </div>

                            <div class="col-6">
                                <label for="sort_order" class="form-label fw-bold small text-muted">Urutan</label>
                                <input type="number" name="sort_order" id="sort_order" class="form-control @error('sort_order') is-invalid @enderror" value="{{ old('sort_order', $package->sort_order) }}" style="border-radius: 10px; border-color: #eadfd6;">
                            </div>
                        </div>

                        <hr class="my-4">

                        <h5 class="fw-bold mb-3" style="color: #211313;"><i class="bi bi-toggles text-gold"></i> Oops & Aturan Layanan</h5>
                        <div class="row g-2">
                            <div class="col-12">
                                <div class="form-check form-switch py-1">
                                    <input class="form-check-input" type="checkbox" name="butuh_makeup" id="butuh_makeup" value="1" {{ $package->butuh_makeup ? 'checked' : '' }}>
                                    <label class="form-check-label text-secondary" for="butuh_makeup">Layanan ini membutuhkan kehadiran MUA (Makeup)</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-check form-switch py-1">
                                    <input class="form-check-input" type="checkbox" name="butuh_baju" id="butuh_baju" value="1" {{ $package->butuh_baju ? 'checked' : '' }}>
                                    <label class="form-check-label text-secondary" for="butuh_baju">Layanan ini menyewa koleksi Baju</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-check form-switch py-1">
                                    <input class="form-check-input" type="checkbox" name="softlens_wajib_pilih" id="softlens_wajib_pilih" value="1" {{ $package->softlens_wajib_pilih ? 'checked' : '' }}>
                                    <label class="form-check-label text-secondary" for="softlens_wajib_pilih">Customer wajib memilih opsi Softlens saat checkout</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-check form-switch py-1">
                                    <input class="form-check-input" type="checkbox" name="is_popular" id="is_popular" value="1" {{ $package->is_popular ? 'checked' : '' }}>
                                    <label class="form-check-label text-secondary" for="is_popular">Tandai paket sebagai Populer (Rekomendasi)</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Package Items -->
                <div class="col-12 col-lg-5">
                    <div class="card p-4" style="border-radius: 18px; border: 1px solid #eadfd6; background: #fff;">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold mb-0" style="color: #211313;"><i class="bi bi-list-task text-gold"></i> Item Bawaan Paket</h5>
                            <button type="button" id="btn-add-item" class="btn btn-sm btn-outline-dark fw-bold" style="border-radius: 8px;">
                                <i class="bi bi-plus"></i> Tambah Item
                            </button>
                        </div>

                        <p class="small text-muted mb-3">Tentukan barang/jasa apa saja yang termasuk dalam paket ini. Klik tombol Tambah Item untuk menambah baris.</p>

                        <div id="items-container" class="d-flex flex-column gap-3">
                            <!-- Existing Items -->
                            @foreach($package->items as $index => $item)
                                <div class="item-row p-3 border rounded position-relative" style="border-color: #eadfd6 !important; background: #fdfaf7;">
                                    <button type="button" class="btn-remove-item btn-close position-absolute top-0 end-0 m-2" style="font-size: 10px;" aria-label="Remove"></button>

                                    <div class="row g-2">
                                        <div class="col-12">
                                            <label class="form-label text-muted small fw-bold mb-1">Nama Item/Layanan</label>
                                            <input type="text" name="items[{{ $index }}][name]" class="form-control form-control-sm" value="{{ $item->name }}" placeholder="Contoh: Henna pengantin" required style="border-radius: 6px;">
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label text-muted small fw-bold mb-1">Jumlah</label>
                                            <input type="number" name="items[{{ $index }}][quantity]" class="form-control form-control-sm" value="{{ $item->quantity }}" min="1" required style="border-radius: 6px;">
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label text-muted small fw-bold mb-1">Satuan</label>
                                            <input type="text" name="items[{{ $index }}][unit]" class="form-control form-control-sm" value="{{ $item->unit }}" placeholder="orang / set" required style="border-radius: 6px;">
                                        </div>
                                        <div class="col-12">
                                            <div class="form-check form-switch py-1 mb-1">
                                                <input class="form-check-input is-pihak-lain-checkbox" type="checkbox" name="items[{{ $index }}][is_pihak_lain]" id="is_pihak_lain_{{ $index }}" value="1" {{ $item->is_pihak_lain ? 'checked' : '' }}>
                                                <label class="form-check-label text-secondary small" for="is_pihak_lain_{{ $index }}">Dikerjakan Pihak Ketiga (Vendor luar)</label>
                                            </div>
                                        </div>
                                        <div class="col-12 biaya-pihak-lain-container {{ $item->is_pihak_lain ? '' : 'd-none' }}">
                                            <label class="form-label text-muted small fw-bold mb-1">Biaya Vendor / Pihak Ketiga (Rp)</label>
                                            <input type="number" name="items[{{ $index }}][biaya_pihak_lain]" class="form-control form-control-sm" value="{{ $item->biaya_pihak_lain }}" min="0" style="border-radius: 6px;">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label text-muted small fw-bold mb-1">Keterangan Tambahan</label>
                                            <input type="text" name="items[{{ $index }}][keterangan]" class="form-control form-control-sm" value="{{ $item->keterangan }}" placeholder="Catatan item (optional)" style="border-radius: 6px;">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <button type="submit" class="btn btn-dark w-100 fw-bold py-2 mt-4" style="border-radius: 10px; background: #211313; border: none;">
                            Perbarui Paket & Item
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </section>

    <!-- Item Template Row -->
    <template id="item-row-template">
        <div class="item-row p-3 border rounded position-relative" style="border-color: #eadfd6 !important; background: #fdfaf7;">
            <button type="button" class="btn-remove-item btn-close position-absolute top-0 end-0 m-2" style="font-size: 10px;" aria-label="Remove"></button>

            <div class="row g-2">
                <div class="col-12">
                    <label class="form-label text-muted small fw-bold mb-1">Nama Item/Layanan</label>
                    <input type="text" name="items[INDEX][name]" class="form-control form-control-sm" placeholder="Contoh: Henna pengantin" required style="border-radius: 6px;">
                </div>
                <div class="col-6">
                    <label class="form-label text-muted small fw-bold mb-1">Jumlah</label>
                    <input type="number" name="items[INDEX][quantity]" class="form-control form-control-sm" value="1" min="1" required style="border-radius: 6px;">
                </div>
                <div class="col-6">
                    <label class="form-label text-muted small fw-bold mb-1">Satuan</label>
                    <input type="text" name="items[INDEX][unit]" class="form-control form-control-sm" placeholder="orang / set / x" value="x" required style="border-radius: 6px;">
                </div>
                <div class="col-12">
                    <div class="form-check form-switch py-1 mb-1">
                        <input class="form-check-input is-pihak-lain-checkbox" type="checkbox" name="items[INDEX][is_pihak_lain]" id="is_pihak_lain_INDEX" value="1">
                        <label class="form-check-label text-secondary small" for="is_pihak_lain_INDEX">Dikerjakan Pihak Ketiga (Vendor luar)</label>
                    </div>
                </div>
                <div class="col-12 biaya-pihak-lain-container d-none">
                    <label class="form-label text-muted small fw-bold mb-1">Biaya Vendor / Pihak Ketiga (Rp)</label>
                    <input type="number" name="items[INDEX][biaya_pihak_lain]" class="form-control form-control-sm" value="0" min="0" style="border-radius: 6px;">
                </div>
                <div class="col-12">
                    <label class="form-label text-muted small fw-bold mb-1">Keterangan Tambahan</label>
                    <input type="text" name="items[INDEX][keterangan]" class="form-control form-control-sm" placeholder="Catatan item (optional)" style="border-radius: 6px;">
                </div>
            </div>
        </div>
    </template>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('items-container');
            const addButton = document.getElementById('btn-add-item');
            const template = document.getElementById('item-row-template').innerHTML;

            // Set index to the count of existing items
            let itemIndex = {{ $package->items->count() }};

            // Bind events for existing rows
            const existingRows = container.querySelectorAll('.item-row');
            existingRows.forEach(row => {
                row.querySelector('.btn-remove-item').addEventListener('click', function() {
                    row.remove();
                });

                const checkbox = row.querySelector('.is-pihak-lain-checkbox');
                const vendorCostContainer = row.querySelector('.biaya-pihak-lain-container');
                checkbox.addEventListener('change', function() {
                    if (this.checked) {
                        vendorCostContainer.classList.remove('d-none');
                    } else {
                        vendorCostContainer.classList.add('d-none');
                        vendorCostContainer.querySelector('input').value = 0;
                    }
                });
            });

            function addItemRow() {
                const html = template.replace(/INDEX/g, itemIndex);
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = html;
                const row = tempDiv.firstElementChild;

                row.querySelector('.btn-remove-item').addEventListener('click', function() {
                    row.remove();
                });

                const checkbox = row.querySelector('.is-pihak-lain-checkbox');
                const vendorCostContainer = row.querySelector('.biaya-pihak-lain-container');
                checkbox.addEventListener('change', function() {
                    if (this.checked) {
                        vendorCostContainer.classList.remove('d-none');
                    } else {
                        vendorCostContainer.classList.add('d-none');
                        vendorCostContainer.querySelector('input').value = 0;
                    }
                });

                container.appendChild(row);
                itemIndex++;
            }

            addButton.addEventListener('click', addItemRow);
        });
    </script>
@endsection
