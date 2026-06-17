@extends('layouts.owner', ['title' => 'Pengaturan WhatsApp — LYB'])

@section('owner_content')
    <header class="lyb-admin-page-header">
        <div>
            <h2>Pengaturan WhatsApp</h2>
            <p>WhatsApp Makeup ditangani Owner, WhatsApp Baju ditangani Admin.</p>
        </div>
    </header>

    <section class="lyb-admin-section">
        <div class="card" style="border-radius: 12px; border: 1px solid #eadfd6;">
            <div class="card-body p-4">
                <form action="{{ route('owner.whatsapp.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="nomor_makeup_paket" class="form-label fw-bold" style="font-size: 14px; color: #211313;">WhatsApp Paket & Makeup (Owner)</label>
                        <input type="text" class="form-control" id="nomor_makeup_paket" name="nomor_makeup_paket"
                               value="{{ old('nomor_makeup_paket', $setting->nomor_makeup_paket ?? '') }}"
                               placeholder="+62 812-3456-7890" required style="border-radius: 8px;">
                        <small class="text-muted">Gunakan format +62 atau awalan 08.</small>
                    </div>

                    <div class="mb-4">
                        <label for="nomor_baju" class="form-label fw-bold" style="font-size: 14px; color: #211313;">WhatsApp Baju (Admin)</label>
                        <input type="text" class="form-control" id="nomor_baju" name="nomor_baju"
                               value="{{ old('nomor_baju', $setting->nomor_baju ?? '') }}"
                               placeholder="+62 813-9876-5432" style="border-radius: 8px;">
                    </div>

                    <div class="mb-4">
                        <label for="template_makeup" class="form-label fw-bold" style="font-size: 14px; color: #211313;">Template Pesan Booking</label>
                        <textarea class="form-control" id="template_makeup" name="template_makeup" rows="3"
                                  placeholder="Halo Kak, saya ingin booking layanan {paket} untuk tanggal {tanggal}. Mohon info lebih lanjut. Terima kasih."
                                  style="border-radius: 8px;">{{ old('template_makeup', $setting->template_makeup ?? '') }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label for="template_baju" class="form-label fw-bold" style="font-size: 14px; color: #211313;">Template Pesan Konsultasi Baju</label>
                        <textarea class="form-control" id="template_baju" name="template_baju" rows="3"
                                  placeholder="Halo Admin, saya ingin tanya ketersediaan baju {paket}. Apakah masih tersedia?"
                                  style="border-radius: 8px;">{{ old('template_baju', $setting->template_baju ?? '') }}</textarea>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-dark fw-bold px-4 py-2" style="border-radius: 10px; background: #211313; border: none;">Simpan Pengaturan</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
