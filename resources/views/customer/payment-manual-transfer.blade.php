@extends('layouts.app', ['title' => 'Transfer Manual — ' . $booking->booking_code])

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/payment-instruction.css') }}">
    <style>
        .manual-transfer-card {
            background: #fff;
            border: 1px solid #eadfd6;
            border-radius: 20px;
            padding: 32px;
            max-width: 620px;
            margin: 0 auto;
        }
        .bank-info-card {
            background: #fdfaf6;
            border: 1px solid #f0ebe5;
            border-radius: 14px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .bank-info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px dashed #eadfd6;
        }
        .bank-info-row:last-child { border-bottom: none; }
        .bank-info-row .label {
            font-size: 13px;
            color: #8a7a72;
        }
        .bank-info-row .value {
            font-weight: 700;
            color: #211313;
            font-size: 14px;
        }
        .bank-info-row .value.highlight {
            color: #b08a42;
            font-size: 16px;
        }
        .copy-btn {
            background: none;
            border: 1px solid #eadfd6;
            border-radius: 8px;
            padding: 4px 10px;
            cursor: pointer;
            font-size: 12px;
            color: #b08a42;
            transition: all 0.2s;
            margin-left: 8px;
        }
        .copy-btn:hover {
            background: #b08a42;
            color: #fff;
        }
        .upload-zone {
            border: 2px dashed #eadfd6;
            border-radius: 14px;
            padding: 30px 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #fdfaf6;
            margin-bottom: 20px;
        }
        .upload-zone:hover, .upload-zone.dragover {
            border-color: #b08a42;
            background: #fffdf5;
        }
        .upload-zone i {
            font-size: 36px;
            color: #b08a42;
            margin-bottom: 10px;
        }
        .upload-zone p {
            font-size: 14px;
            color: #8a7a72;
            margin: 0;
        }
        .upload-zone .file-name {
            font-weight: 600;
            color: #211313;
            margin-top: 8px;
        }
        .upload-preview {
            max-height: 200px;
            border-radius: 12px;
            margin-top: 12px;
            border: 1px solid #eadfd6;
        }
        .btn-upload-submit {
            width: 100%;
            padding: 14px 20px;
            border-radius: 50px;
            background: #211313;
            color: #fff;
            font-weight: 700;
            font-size: 15px;
            border: none;
            cursor: pointer;
            transition: all 0.25s ease;
        }
        .btn-upload-submit:hover {
            background: #3a2222;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(33, 19, 19, 0.15);
        }
        .btn-upload-submit:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }
        .steps-list {
            counter-reset: step-counter;
            list-style: none;
            padding: 0;
            margin: 16px 0;
        }
        .steps-list li {
            counter-increment: step-counter;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 10px 0;
            font-size: 13.5px;
            color: #6f625c;
            line-height: 1.5;
        }
        .steps-list li::before {
            content: counter(step-counter);
            background: #b08a42;
            color: #fff;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
            flex-shrink: 0;
        }
    </style>
@endpush

@section('content')
<section class="payment-instruction-section" style="padding: 40px 0;">
    <div class="container">
        <div class="mb-4">
            <a href="{{ route('customer.dashboard') }}" class="text-decoration-none d-inline-flex align-items-center gap-2 back-link" style="font-size: 14px; font-weight: 500; color: #7d776c;">
                <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
            </a>
        </div>

        <div class="text-center mb-4">
            <span style="color: #b08a42; letter-spacing: 2px; text-transform: uppercase; font-size: 0.8rem; font-weight: 700;">Transfer Manual</span>
            <h1 style="font-family: Georgia, serif; font-size: 1.8rem; color: #211313; margin-top: 6px;">Upload Bukti Transfer DP</h1>
            <p style="color: #8a7a72; font-size: 14px;">Kode Booking: <strong style="color: #211313;">{{ $booking->booking_code }}</strong></p>
        </div>

        <div class="manual-transfer-card">
            {{-- Bank Account Info --}}
            <h5 style="font-weight: 700; color: #211313; font-size: 15px; margin-bottom: 16px;">
                <i class="bi bi-bank me-2" style="color: #b08a42;"></i>Rekening Tujuan Transfer
            </h5>

            <div class="bank-info-card">
                <div class="bank-info-row">
                    <span class="label">Bank</span>
                    <span class="value">BCA</span>
                </div>
                <div class="bank-info-row">
                    <span class="label">No. Rekening</span>
                    <span class="value">
                        <span id="rekeningNumber">1234567890</span>
                        <button class="copy-btn" onclick="copyText('rekeningNumber')" title="Salin">
                            <i class="bi bi-copy"></i>
                        </button>
                    </span>
                </div>
                <div class="bank-info-row">
                    <span class="label">Atas Nama</span>
                    <span class="value">Lisa Yuli Belti</span>
                </div>
                <div class="bank-info-row">
                    <span class="label">Nominal Transfer</span>
                    <span class="value highlight">Rp{{ number_format($booking->dp_amount, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="alert alert-warning rounded-3 py-2 small mb-4" style="background: #fffbeb; border-color: #f5c842; color: #92400e;">
                <i class="bi bi-info-circle me-1"></i>
                Pastikan nominal transfer sesuai. Kirimkan transfer Anda sebelum melakukan upload bukti.
            </div>

            {{-- Steps --}}
            <h6 style="font-weight: 700; color: #211313; font-size: 14px;">Langkah-langkah:</h6>
            <ol class="steps-list">
                <li>Transfer DP sebesar <strong>Rp{{ number_format($booking->dp_amount, 0, ',', '.') }}</strong> ke rekening di atas.</li>
                <li>Screenshot atau foto bukti transfer Anda.</li>
                <li>Upload bukti transfer di form bawah ini.</li>
                <li>Tunggu konfirmasi dari admin (biasanya 1x24 jam).</li>
            </ol>

            <hr style="border-color: #f0ebe5; margin: 24px 0;">

            {{-- Upload Form --}}
            <h5 style="font-weight: 700; color: #211313; font-size: 15px; margin-bottom: 16px;">
                <i class="bi bi-upload me-2" style="color: #b08a42;"></i>Upload Bukti Transfer
            </h5>

            <form action="{{ route('customer.payment.upload-bukti', $booking->booking_code) }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                @csrf

                <div class="upload-zone" id="uploadZone" onclick="document.getElementById('proofInput').click();">
                    <i class="bi bi-cloud-arrow-up"></i>
                    <p>Klik atau seret gambar bukti transfer ke sini</p>
                    <p style="font-size: 12px; color: #bbb;">JPG, PNG — Maks. 10MB</p>
                    <div class="file-name" id="fileName" style="display: none;"></div>
                    <img class="upload-preview" id="previewImg" style="display: none;" alt="Preview bukti transfer">
                </div>

                <input type="file" name="proof_image" id="proofInput" accept="image/jpeg,image/png,image/jpg" style="display: none;" onchange="handleFileSelect(this)">

                @error('proof_image')
                    <div class="alert alert-danger rounded-3 py-2 small mb-3">{{ $message }}</div>
                @enderror

                <button type="submit" class="btn-upload-submit" id="submitBtn" disabled>
                    <i class="bi bi-send me-2"></i>Kirim Bukti Transfer
                </button>
            </form>

            <div class="text-center mt-3">
                <a href="https://wa.me/6281227545591?text=Halo%20admin%20LYB,%20saya%20sudah%20transfer%20DP%20untuk%20booking%20{{ $booking->booking_code }}" target="_blank" style="font-size: 13px; color: #25d366; text-decoration: none; font-weight: 600;">
                    <i class="bi bi-whatsapp me-1"></i>Konfirmasi via WhatsApp
                </a>
            </div>
        </div>
    </div>
</section>

<script>
function copyText(elementId) {
    const text = document.getElementById(elementId).innerText;
    navigator.clipboard.writeText(text).then(() => {
        alert('Berhasil disalin!');
    });
}

function handleFileSelect(input) {
    const file = input.files[0];
    if (!file) return;

    const fileName = document.getElementById('fileName');
    const previewImg = document.getElementById('previewImg');
    const submitBtn = document.getElementById('submitBtn');
    const uploadZone = document.getElementById('uploadZone');

    fileName.textContent = file.name;
    fileName.style.display = 'block';
    submitBtn.disabled = false;

    const reader = new FileReader();
    reader.onload = (e) => {
        previewImg.src = e.target.result;
        previewImg.style.display = 'block';
    };
    reader.readAsDataURL(file);

    uploadZone.querySelector('i').className = 'bi bi-check-circle-fill';
    uploadZone.querySelector('i').style.color = '#2d6e25';
}

// Drag and drop
const zone = document.getElementById('uploadZone');
if (zone) {
    zone.addEventListener('dragover', (e) => {
        e.preventDefault();
        zone.classList.add('dragover');
    });
    zone.addEventListener('dragleave', () => {
        zone.classList.remove('dragover');
    });
    zone.addEventListener('drop', (e) => {
        e.preventDefault();
        zone.classList.remove('dragover');
        const input = document.getElementById('proofInput');
        input.files = e.dataTransfer.files;
        handleFileSelect(input);
    });
}
</script>
@endsection
