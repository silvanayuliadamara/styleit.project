<?php

namespace App\Http\Requests\Cart;

use App\Models\ServicePackage;
use Illuminate\Foundation\Http\FormRequest;

class StoreCartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $package = ServicePackage::with('category')->find($this->package_id);
        $categorySlug = $package->category->slug ?? '';
        
        $is2xMakeup = $package && in_array($package->code, ['PKG-MU-2X', 'PKG-WED-SILVER', 'PKG-WED-GOLD', 'PKG-WED-GOLD-L']);
        $is3xMakeup = $package && in_array($package->code, ['PKG-MU-3X', 'PKG-WED-DIAMOND-P', 'PKG-WED-DIAMOND-L']);

        $rules = [
            'package_id' => ['required', 'integer'],
            'booking_date' => ['required', 'date', 'after_or_equal:today'],
            'softlens' => ['required', 'boolean'],
            'addons' => ['nullable', 'array'],
            'addons.*' => ['integer'],
            'action' => ['nullable', 'in:cart,checkout'],
            'edit_key' => ['nullable', 'string'],
        ];

        if ($is2xMakeup || $is3xMakeup) {
            $rules['booking_date_2'] = ['required', 'date', 'after_or_equal:today'];
        } else {
            $rules['booking_date_2'] = ['nullable', 'date'];
        }

        if ($is3xMakeup) {
            $rules['booking_date_3'] = ['required', 'date', 'after_or_equal:today'];
        } else {
            $rules['booking_date_3'] = ['nullable', 'date'];
        }

        if ($categorySlug === 'wedding' || $categorySlug === 'prewedding' || $categorySlug === 'regular') {
            $rules['slot_waktu'] = ['required', 'string', 'in:pagi,siang,sore'];
            if ($is2xMakeup || $is3xMakeup) {
                $rules['slot_waktu_2'] = ['required', 'string', 'in:pagi,siang,sore'];
            } else {
                $rules['slot_waktu_2'] = ['nullable', 'string'];
            }
            if ($is3xMakeup) {
                $rules['slot_waktu_3'] = ['required', 'string', 'in:pagi,siang,sore'];
            } else {
                $rules['slot_waktu_3'] = ['nullable', 'string'];
            }
        } else {
            $rules['slot_waktu'] = ['nullable', 'string'];
            $rules['slot_waktu_2'] = ['nullable', 'string'];
            $rules['slot_waktu_3'] = ['nullable', 'string'];
        }

        if ($categorySlug === 'baju') {
            $rules['tanggal_fitting'] = ['required', 'date', 'before:booking_date'];
        } else {
            $rules['tanggal_fitting'] = ['nullable', 'date'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'booking_date.required' => 'Tanggal booking wajib dipilih.',
            'booking_date_2.required' => 'Tanggal booking kedua wajib dipilih untuk paket ini.',
            'booking_date_3.required' => 'Tanggal booking ketiga wajib dipilih untuk paket ini.',
            'softlens.required' => 'Status penggunaan softlens wajib dipilih.',
            'slot_waktu.required' => 'Slot waktu MUA pertama wajib dipilih.',
            'slot_waktu_2.required' => 'Slot waktu MUA kedua wajib dipilih.',
            'slot_waktu_3.required' => 'Slot waktu MUA ketiga wajib dipilih.',
            'tanggal_fitting.required' => 'Tanggal fitting wajib dipilih.',
            'tanggal_fitting.before' => 'Tanggal fitting harus sebelum tanggal booking.',
        ];
    }
}