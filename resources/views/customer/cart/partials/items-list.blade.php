@php
    $checkoutKeys = session('checkout_keys', []);
    $cartKeys = collect($cart)->pluck('key')->all();
    $isCheckedAll = empty($checkoutKeys) || count(array_intersect($cartKeys, $checkoutKeys)) === count($cartKeys);
@endphp

{{-- Select All Bar --}}
<div class="cart-select-all-bar mb-3">
    <div class="cart-checkbox-wrapper" style="padding-right: 16px;">
        <input type="checkbox" id="selectAllCheckbox" class="cart-item-checkbox" {{ $isCheckedAll ? 'checked' : '' }}>
    </div>
    <label for="selectAllCheckbox" class="outfit-font select-all-label">
        Pilih Semua ({{ count($cart) }} Layanan)
    </label>
</div>

<div class="cart-items-container">
    @foreach($cart as $item)
        @php
            $isChecked = $isCheckedAll || in_array($item['key'], $checkoutKeys);
        @endphp
        <div class="cart-card" id="cart-card-{{ $item['key'] }}">
            {{-- Checkbox --}}
            <div class="cart-checkbox-wrapper">
                <input type="checkbox" name="selected_keys[]" value="{{ $item['key'] }}" class="cart-item-checkbox" 
                       data-price="{{ $item['total_price'] }}" 
                       data-dp="{{ $item['dp_amount'] }}" 
                       data-remaining="{{ $item['remaining_payment'] }}"
                       {{ $isChecked ? 'checked' : '' }}>
            </div>

            {{-- Image --}}
            <div class="cart-img-wrapper">
                @if(!empty($item['package_image']))
                    <img src="{{ asset('storage/' . $item['package_image']) }}" alt="{{ $item['package_name'] }}" class="cart-img">
                @else
                    <div class="cart-img-placeholder">
                        <i class="bi bi-stars"></i>
                    </div>
                @endif
            </div>

            {{-- Details --}}
            <div class="cart-details">
                <span class="category-tag outfit-font">{{ $item['category_name'] }}</span>
                <h3>{{ $item['package_name'] }}</h3>
                
                <div class="detail-text outfit-font">
                    <i class="bi bi-calendar-event"></i> 
                    <span>{{ \Illuminate\Support\Carbon::parse($item['booking_date'])->translatedFormat('l, d F Y') }}</span>
                    @if(!empty($item['slot_waktu']))
                        <span class="text-muted">•</span>
                        <span>Slot: {{ ucfirst($item['slot_waktu']) }}</span>
                    @endif
                </div>
                
                @if(strtolower($item['category_name']) !== 'khusus baju' && strtolower($item['category_name']) !== 'baju' && strtolower($item['category_name']) !== 'baju pengantin')
                    <div class="detail-text outfit-font">
                        <i class="bi bi-eye"></i> 
                        <span>Softlens: {{ $item['softlens'] ? 'Ya' : 'Tidak' }}</span>
                    </div>
                @endif
                
                @if(!empty($item['tanggal_fitting']))
                    <div class="detail-text outfit-font">
                        <i class="bi bi-vector-pen"></i> 
                        <span>Tanggal Fitting: {{ \Illuminate\Support\Carbon::parse($item['tanggal_fitting'])->translatedFormat('d F Y') }}</span>
                    </div>
                @endif

                @if(count($item['addons']))
                    <div class="mt-3 d-flex flex-wrap gap-2 align-items-center">
                        <span class="detail-text me-1 mb-0 outfit-font" style="font-size: 12px; font-weight: 500;">
                            <i class="bi bi-plus-circle"></i> Add-on:
                        </span>
                        @foreach($item['addons'] as $addon)
                            <span class="addon-badge outfit-font">
                                <i class="bi bi-plus" style="font-size: 14px; color: var(--lyb-gold); margin-right: -2px;"></i>
                                {{ $addon['name'] }}
                            </span>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Action & Price --}}
            <div class="cart-actions-price">
                {{-- Price Info --}}
                <div class="cart-price-info">
                    <span class="price-label outfit-font">Total Layanan</span>
                    <span class="price-val">Rp{{ number_format($item['total_price'], 0, ',', '.') }}</span>
                    <span class="dp-val outfit-font">DP Rp{{ number_format($item['dp_amount'], 0, ',', '.') }}</span>
                </div>
                
                <div class="cart-button-row">
                    <a href="{{ route('paket.show', $item['package_code']) }}?edit_key={{ $item['key'] }}" class="btn-edit-cart outfit-font">
                        <i class="bi bi-pencil-square"></i> Ubah Detail
                    </a>
                    <button type="button" class="btn-delete-cart" onclick="confirmDelete('{{ $item['key'] }}')" title="Hapus dari keranjang">
                        <i class="bi bi-trash3"></i>
                    </button>
                </div>
            </div>
        </div>
    @endforeach
</div>
