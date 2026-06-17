@extends('layouts.owner', ['title' => 'Edit Addon & Tambahan — LYB'])

@section('owner_content')
    <div class="mb-3">
        <a href="{{ route('owner.addons.index') }}" class="btn btn-sm btn-outline-secondary" style="border-radius: 8px; border-color: #eadfd6; color: #211313;">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Addon
        </a>
    </div>

    <header class="lyb-admin-page-header">
        <div>
            <h2>Edit Addon: {{ $addon->name }}</h2>
            <p>Perbarui rincian harga, kategori terkait, dan opsi varian addon.</p>
        </div>
    </header>

    <section class="lyb-admin-section">
        <form action="{{ route('owner.addons.update', $addon) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-4">
                <!-- Left: Addon Fields -->
                <div class="col-12 col-lg-7">
                    <div class="card p-4 mb-4" style="border-radius: 18px; border: 1px solid #eadfd6; background: #fff;">
                        <h5 class="fw-bold mb-3" style="color: #211313;"><i class="bi bi-info-circle-fill text-gold"></i> Rincian Addon</h5>

                        <div class="row g-3">
                            <div class="col-12">
                                <label for="name" class="form-label fw-bold small text-muted">Nama Addon <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $addon->name) }}" placeholder="Contoh: Makeup Keluarga" required style="border-radius: 10px; border-color: #eadfd6;">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="price" class="form-label fw-bold small text-muted">Harga Default / Mulai Dari (Rp) <span class="text-danger">*</span></label>
                                <input type="number" name="price" id="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $addon->price) }}" placeholder="350000" required style="border-radius: 10px; border-color: #eadfd6;">
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="status" class="form-label fw-bold small text-muted">Status <span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-select" required style="border-radius: 10px; border-color: #eadfd6;">
                                    <option value="aktif" {{ $addon->is_active ? 'selected' : '' }}>Aktif</option>
                                    <option value="nonaktif" {{ !$addon->is_active ? 'selected' : '' }}>Nonaktif</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label for="description" class="form-label fw-bold small text-muted">Deskripsi Singkat</label>
                                <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="3" placeholder="Tulis catatan ringkas tentang tambahan ini..." style="border-radius: 10px; border-color: #eadfd6;">{{ old('description', $addon->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        <h5 class="fw-bold mb-3" style="color: #211313;"><i class="bi bi-tags-fill text-gold"></i> Aktif di Kategori Layanan</h5>
                        <p class="small text-muted mb-3">Pilih di kategori mana addon ini dapat ditambahkan oleh customer saat melakukan booking.</p>
                        <div class="row g-2">
                            @php
                                $addonCatIds = $addon->categories->pluck('id')->toArray();
                            @endphp
                            @foreach($categories as $category)
                                <div class="col-6 col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="category_ids[]" value="{{ $category->id }}" id="category_{{ $category->id }}" {{ in_array($category->id, $addonCatIds) ? 'checked' : '' }}>
                                        <label class="form-check-label text-dark" for="category_{{ $category->id }}">
                                            {{ $category->name }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <hr class="my-4">

                        <h5 class="fw-bold mb-3" style="color: #211313;"><i class="bi bi-people-fill text-gold"></i> Pihak Ketiga / Vendor</h5>
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="form-check form-switch py-1">
                                    <input class="form-check-input" type="checkbox" name="is_pihak_lain" id="is_pihak_lain" value="1" {{ $addon->is_pihak_lain ? 'checked' : '' }}>
                                    <label class="form-check-label text-secondary" for="is_pihak_lain">Addon ini dikerjakan oleh Vendor Luar (Bukan internal LYB)</label>
                                </div>
                            </div>
                            <div class="col-12 {{ $addon->is_pihak_lain ? '' : 'd-none' }}" id="biaya-vendor-container">
                                <label for="biaya_pihak_lain" class="form-label fw-bold small text-muted">Biaya Pihak Ketiga (COGS Vendor) (Rp)</label>
                                <input type="number" name="biaya_pihak_lain" id="biaya_pihak_lain" class="form-control" value="{{ $addon->biaya_pihak_lain }}" placeholder="Biaya modal ke vendor" style="border-radius: 10px; border-color: #eadfd6;">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Addon Options -->
                <div class="col-12 col-lg-5">
                    <div class="card p-4" style="border-radius: 18px; border: 1px solid #eadfd6; background: #fff;">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold mb-0" style="color: #211313;"><i class="bi bi-list-task text-gold"></i> Pilihan Opsi Addon</h5>
                            <button type="button" id="btn-add-option" class="btn btn-sm btn-outline-dark fw-bold" style="border-radius: 8px;">
                                <i class="bi bi-plus"></i> Tambah Opsi
                            </button>
                        </div>

                        <p class="small text-muted mb-3">Jika addon memiliki beberapa opsi varian (misal: "Makeup Anak" / "Makeup Ibu"), silakan masukkan di sini. Jika tidak ada opsi khusus, sistem akan memakai harga default di samping.</p>

                        <div id="options-container" class="d-flex flex-column gap-3">
                            <!-- Existing Options -->
                            @foreach($addon->options as $index => $opt)
                                <div class="option-row p-3 border rounded position-relative" style="border-color: #eadfd6 !important; background: #fdfaf7;">
                                    <button type="button" class="btn-remove-option btn-close position-absolute top-0 end-0 m-2" style="font-size: 10px;" aria-label="Remove"></button>

                                    <div class="row g-2">
                                        <div class="col-12">
                                            <label class="form-label text-muted small fw-bold mb-1">Nama Opsi Varian</label>
                                            <input type="text" name="options[{{ $index }}][nama_option]" class="form-control form-control-sm" value="{{ $opt->nama_option }}" placeholder="Contoh: Hijab Do / Hair Do" required style="border-radius: 6px;">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label text-muted small fw-bold mb-1">Harga Jual Opsi (Rp)</label>
                                            <input type="number" name="options[{{ $index }}][harga]" class="form-control form-control-sm" value="{{ $opt->harga }}" placeholder="150000" required style="border-radius: 6px;">
                                        </div>
                                        <div class="col-12">
                                            <div class="form-check form-switch py-1 mb-1">
                                                <input class="form-check-input opt-is-pihak-lain-checkbox" type="checkbox" name="options[{{ $index }}][is_pihak_lain]" id="opt_is_pihak_lain_{{ $index }}" value="1" {{ $opt->is_pihak_lain ? 'checked' : '' }}>
                                                <label class="form-check-label text-secondary small" for="opt_is_pihak_lain_{{ $index }}">Dikerjakan Vendor luar</label>
                                            </div>
                                        </div>
                                        <div class="col-12 opt-biaya-pihak-lain-container {{ $opt->is_pihak_lain ? '' : 'd-none' }}">
                                            <label class="form-label text-muted small fw-bold mb-1">Biaya Vendor (COGS) (Rp)</label>
                                            <input type="number" name="options[{{ $index }}][biaya_pihak_lain]" class="form-control form-control-sm" value="{{ $opt->biaya_pihak_lain }}" min="0" style="border-radius: 6px;">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <button type="submit" class="btn btn-dark w-100 fw-bold py-2 mt-4" style="border-radius: 10px; background: #211313; border: none;">
                            Perbarui Addon & Opsi
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </section>

    <!-- Option Template Row -->
    <template id="option-row-template">
        <div class="option-row p-3 border rounded position-relative" style="border-color: #eadfd6 !important; background: #fdfaf7;">
            <button type="button" class="btn-remove-option btn-close position-absolute top-0 end-0 m-2" style="font-size: 10px;" aria-label="Remove"></button>

            <div class="row g-2">
                <div class="col-12">
                    <label class="form-label text-muted small fw-bold mb-1">Nama Opsi Varian</label>
                    <input type="text" name="options[INDEX][nama_option]" class="form-control form-control-sm" placeholder="Contoh: Hijab Do / Hair Do" required style="border-radius: 6px;">
                </div>
                <div class="col-12">
                    <label class="form-label text-muted small fw-bold mb-1">Harga Jual Opsi (Rp)</label>
                    <input type="number" name="options[INDEX][harga]" class="form-control form-control-sm" placeholder="150000" required style="border-radius: 6px;">
                </div>
                <div class="col-12">
                    <div class="form-check form-switch py-1 mb-1">
                        <input class="form-check-input opt-is-pihak-lain-checkbox" type="checkbox" name="options[INDEX][is_pihak_lain]" id="opt_is_pihak_lain_INDEX" value="1">
                        <label class="form-check-label text-secondary small" for="opt_is_pihak_lain_INDEX">Dikerjakan Vendor luar</label>
                    </div>
                </div>
                <div class="col-12 opt-biaya-pihak-lain-container d-none">
                    <label class="form-label text-muted small fw-bold mb-1">Biaya Vendor (COGS) (Rp)</label>
                    <input type="number" name="options[INDEX][biaya_pihak_lain]" class="form-control form-control-sm" value="0" min="0" style="border-radius: 6px;">
                </div>
            </div>
        </div>
    </template>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle vendor cost for main addon
            const isPihakLainCheckbox = document.getElementById('is_pihak_lain');
            const biayaVendorContainer = document.getElementById('biaya-vendor-container');

            isPihakLainCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    biayaVendorContainer.classList.remove('d-none');
                } else {
                    biayaVendorContainer.classList.add('d-none');
                    document.getElementById('biaya_pihak_lain').value = 0;
                }
            });

            // Options editor
            const container = document.getElementById('options-container');
            const addButton = document.getElementById('btn-add-option');
            const template = document.getElementById('option-row-template').innerHTML;

            // Set initial index
            let optionIndex = {{ $addon->options->count() }};

            // Bind events for existing option rows
            const existingRows = container.querySelectorAll('.option-row');
            existingRows.forEach(row => {
                row.querySelector('.btn-remove-option').addEventListener('click', function() {
                    row.remove();
                });

                const checkbox = row.querySelector('.opt-is-pihak-lain-checkbox');
                const vendorCostContainer = row.querySelector('.opt-biaya-pihak-lain-container');
                checkbox.addEventListener('change', function() {
                    if (this.checked) {
                        vendorCostContainer.classList.remove('d-none');
                    } else {
                        vendorCostContainer.classList.add('d-none');
                        vendorCostContainer.querySelector('input').value = 0;
                    }
                });
            });

            function addOptionRow() {
                const html = template.replace(/INDEX/g, optionIndex);
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = html;
                const row = tempDiv.firstElementChild;

                row.querySelector('.btn-remove-option').addEventListener('click', function() {
                    row.remove();
                });

                const checkbox = row.querySelector('.opt-is-pihak-lain-checkbox');
                const vendorCostContainer = row.querySelector('.opt-biaya-pihak-lain-container');
                checkbox.addEventListener('change', function() {
                    if (this.checked) {
                        vendorCostContainer.classList.remove('d-none');
                    } else {
                        vendorCostContainer.classList.add('d-none');
                        vendorCostContainer.querySelector('input').value = 0;
                    }
                });

                container.appendChild(row);
                optionIndex++;
            }

            addButton.addEventListener('click', addOptionRow);
        });
    </script>
@endsection
